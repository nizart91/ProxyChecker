<?php

namespace App\Service;

use App\DTO\ProxyResult;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\TransferStats;
use GuzzleHttp\Promise\Utils;

class ProxyChecker
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 15, // 15 секунд таймаута
        ]);
    }

    public function checkProxy(string $proxy): ?ProxyResult
    {
        list($ip, $port) = explode(':', $proxy);

        $promises = [];
        $results = [];
        $times = [];

        $request = new Request('GET', 'http://ip-api.com/json');
        foreach ([CURLPROXY_HTTPS, CURLPROXY_HTTP, CURLPROXY_SOCKS4, CURLPROXY_SOCKS5] as $proxyType) {
            $promises[$proxyType] = $this->client->sendAsync($request, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'curl' => [
                    CURLOPT_PROXY => $ip,
                    CURLOPT_PROXYPORT => $port,
                    CURLOPT_PROXYTYPE => $proxyType,
                ],
                'on_stats' => function (TransferStats $stats) use (&$times, $proxyType) {
                    $times[$proxyType] = $stats->getTransferTime();
                },
            ])->then(function ($response) use (&$results, $proxy, $proxyType) {
                    $data = json_decode($response->getBody(), true);
                    if ($data['status'] === 'success') {
                        $results[] = (new ProxyResult())
                            ->setProxy($proxy)
                            ->setProxyType($proxyType)
                            ->setRealIp($data['query'])
                            ->setLocation(implode(', ', array_diff([
                                $data['country'] ?? null,
                                $data['regionName'] ?? null,
                                $data['city'] ?? null,
                            ], [null])))
                        ;
                    }
                });
        }

        Utils::settle($promises)->wait();

        if (empty($results)) {
            return null;
        }

        /** @var ProxyResult $result */
        $result = array_values($results)[0];

        return $result->setTimeout(intval(ceil($times[$result->getProxyType()] * 1000)));
    }
}
