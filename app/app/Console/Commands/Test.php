<?php

namespace App\Console\Commands;

use App\Jobs\CheckProxy;
use App\Models\Checker\Proxy;
use App\Models\Checker\Task;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

//        $proxies = Proxy::whereStartedAt(null)->get();
        $proxies = Proxy::all();

        foreach ($proxies as $proxy) {
            CheckProxy::dispatch($proxy->id);
        }

        return 1;
        $client = new Client([
            'timeout' => 10, // 10 секунд таймаута
        ]);


        $proxy = "https://200.95.184.58:999";
        $proxy = "https://20.206.106.192:8123";
        $proxy = "http://177.234.241.25:999";

        $response = $client->get('http://ip-api.com/json', [
            'proxy' => $proxy,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        $this->info(print_r($data,1));

        return 1;
        $ip = '23.152.40.14';
        $port = '3128';
        $request = new Request('GET', 'http://ip-api.com/json');
        $response = $client->send($request, [
            'curl'  => [
                CURLOPT_PROXY => $ip,
                CURLOPT_PROXYPORT => $port,
                CURLOPT_PROXYTYPE => CURLPROXY_HTTP | CURLPROXY_HTTPS,
//                    CURLOPT_PROXYUSERPWD => '*:*',
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        $this->info(print_r($data,1));
        return 1;
        $task = new Task();
        $task->created_at = $task->freshTimestamp();
        $task->updated_at = $task->freshTimestamp();
        $task->save();

        foreach (range(1, 5) as $i) {
            $proxy = new Proxy();
            $proxy->ip = '10.0.0.1:1234' . $i;
            $proxy->task_id = $task->id;
            $proxy->save();

            CheckProxy::dispatch($proxy->id);
        }

    }
}
