<?php

namespace App\Services\Exchanger;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;

class FCSApi implements CryptoExchangerInterface
{
    private $client;
    private $api_key;

    public function __construct()
    {
        $this->api_key = config('exchanger.fcsapi.api_key');
        $this->client = new Client();
    }

    public function getPrice(string $firstSymbol, string $secondSymbol): float|JsonResponse|array
    {
        if ($firstSymbol === "USDT") $firstSymbol = "USD";
        if ($secondSymbol === "USDT") $secondSymbol = "USD";

        $url = "https://fcsapi.com/api-v3/crypto/latest";
        $options = [
            "access_key" => $this->api_key,
            "symbol" => $firstSymbol . "/" . $secondSymbol,
        ];
        $response = $this->requester($url, $options);
        try {
            return floatval($response['response'][0]['o']);

        } catch (Exception $e) {

            return 0;
        }

    }

    private function requester($url, $options)
    {

        $response = $this->client->get($url, [
            'query' => $options
        ]);

        $statusCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        if ($statusCode === 200) {
            $data = json_decode($responseBody, true);
            return $data;
        } else {
            return response()->json(['error' => "API request failed with status code: $statusCode"], $statusCode);
        }

    }

}
