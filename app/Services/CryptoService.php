<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Rate;
use App\Repositories\Base\BaseRepositoryInterface;
use App\Repositories\CurrencyRepository;
use App\Repositories\RateRepository;
use App\Services\Exchanger\CryptoExchangerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CryptoService implements CryptoServiceInterface
{
    private CryptoExchangerInterface $exchangerInstance;

    public function __construct(
        CryptoExchangerInterface        $exchangerInstance,
        private BaseRepositoryInterface $currencyRepository,
        private BaseRepositoryInterface $rateRepository,
    )
    {
        $this->exchangerInstance = $exchangerInstance;
    }

    public function getPrice($firstSymbol, $secondSymbol): float|JsonResponse|null
    {
        $rate = $this->exchangerInstance->getPrice($firstSymbol, $secondSymbol);
        if ($rate == 0 || $rate == '0') {
            return null;
        }
        return $rate;
    }

    // Cache system for reading Rates
    public function rateReader($firstSymbol, $secondSymbol): array
    {
        return Cache::remember("rate_$firstSymbol/$secondSymbol", 60, function () use ($firstSymbol, $secondSymbol) {
            $currencyId = $this->currencyRepository->findBySymbol($firstSymbol)->id;
            $sourceCurrencyId = $this->currencyRepository->findBySymbol($secondSymbol)->id;
            $rateObj = $this->rateRepository->getRateRecord($currencyId, $sourceCurrencyId);
            $price = $this->rateRepository->getPriceForTwoCurrencies($currencyId, $sourceCurrencyId);
            return [
                'rate_id' => $rateObj->id,
                'price' => $price,
            ];
        });
    }
}
