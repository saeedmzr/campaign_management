<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use Telegram\Bot\HttpClients\HttpClientInterface;

class CustomTelegramRequestHandler implements HttpClientInterface
{

    private int $timeOut;
    private int $connectionTimeOut;

    public function send(string $url,
                         string $method,
                         array  $headers = [],
                         array  $options = [],
                         bool   $isAsyncRequest = false): ResponseInterface|PromiseInterface|null
    {

        $proxy = config("telegram.proxy");

        $client = new Client([
            'proxy' => $proxy,
        ]);

        return $client->request($method, $url, $options);

    }

    public function getTimeOut(): int
    {
        return $this->timeOut;
    }

    public function setTimeOut(int $timeOut): static
    {
        $this->timeOut = $timeOut;
        return $this;
    }

    public function getConnectTimeOut(): int
    {
        return $this->connectionTimeOut;
    }

    public function setConnectTimeOut(int $connectTimeOut): static
    {
        $this->connectionTimeOut = $connectTimeOut;
        return $this;
    }

}
