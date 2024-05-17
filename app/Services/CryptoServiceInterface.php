<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

interface CryptoServiceInterface
{
    public function getPrice($firstSymbol, $secondSymbol): float|JsonResponse|null;

    public function rateReader($firstSymbol, $secondSymbol): array;
}
