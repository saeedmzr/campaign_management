<?php

namespace App\Services\Exchanger;

use Illuminate\Http\JsonResponse;

interface CryptoExchangerInterface
{
    public function getPrice(string $firstSymbol, string $secondSymbol): float|JsonResponse|array;
}
