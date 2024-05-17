<?php

namespace App\Services;

use App\Models\Currency;
use App\Repositories\CurrencyRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RateRepository;
use App\Services\Exchanger\CryptoExchangerInterface;
use App\Services\Exchanger\FCSApi;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        private OrderRepository    $orderRepository,
        private CurrencyRepository $currencyRepository,
    )
    {

    }

    public function createOrder(CryptoServiceInterface $cryptoService, array $data): \Illuminate\Database\Eloquent\Model|false
    {
        try {
            DB::beginTransaction();
            $email = $data['email'];
            $amount = $data['amount'];
//            Cache storage for reading rates
            $rate = $cryptoService->rateReader($data['first_symbol'], $data['second_symbol']);
            $rateId = $rate['rate_id'];
            $ratePrice = $rate['price'];
            $amountReceived = $amount / $ratePrice;
            $payload = [
                "rate_id" => $rateId,
                "amount_received" => $amountReceived,
                "amount_paid" => $amount,
                "rate_state_value" => $ratePrice,
                "email_address" => $email,
            ];
            $order = $this->orderRepository->create($payload);
            $this->orderRepository->generateTrackingCode($order->id);
            DB::commit();
            return $order->fresh();
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }

    }
}
