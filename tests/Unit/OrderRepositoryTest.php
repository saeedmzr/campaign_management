<?php

namespace Tests\Unit;

use App\Models\Currency;
use App\Models\Order;
use App\Models\Rate;
use App\Repositories\CurrencyRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RateRepository;
use Database\Seeders\CurrencySeeder;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $orderRepository;

    public function setUp(): void
    {
        parent::setUp();

        app(CurrencySeeder::class)->run();
        $this->orderRepository = new OrderRepository(new Order());

    }

    public function test_get_order_by_its_tracking_code()
    {

        $sampleTrackinkCode = "123qazwsxrfv";

        $orderActual = Order::factory(
            ['tracking_code' => $sampleTrackinkCode]
        )->create();
        $orderExcepted = $this->orderRepository->getOrderByTrackingCode($sampleTrackinkCode);

        $this->assertEquals($orderActual->id, $orderExcepted->id);
    }

    public function test_generate_tracking_code()
    {

        $orderActual = Order::factory()->create();
        $this->orderRepository->generateTrackingCode($orderActual->id);
        $orderExcepted = $this->orderRepository->findById($orderActual->id);
        $this->assertNotNull($orderExcepted->tracking_code);
    }

}

