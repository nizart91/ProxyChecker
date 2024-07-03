<?php

namespace App\Jobs;

use App\Models\Checker\Proxy;
use App\Service\ProxyChecker;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckProxy implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $proxyId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $proxyId)
    {
        $this->proxyId = $proxyId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $proxy = Proxy::query()->where('id', $this->proxyId)->firstOrFail();

        $proxy->started_at = $proxy->freshTimestamp();
        $service = new ProxyChecker();
        $result = $service->checkProxy($proxy->ip);

        if ($result) {
            $proxy->proxy_type = $result->getProxyType();
            $proxy->location = $result->getLocation();
            $proxy->timeout = $result->getTimeout();
            $proxy->real_ip = $result->getRealIp();
            $proxy->status = true;
        } else {
            $proxy->status = false;
        }
        $proxy->finished_at = $proxy->freshTimestamp();
        $proxy->save();
        $proxy->task->updateLast();
    }
}
