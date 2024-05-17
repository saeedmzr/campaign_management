<?php

namespace Tests\Unit;

use App\Models\Rate;
use App\Repositories\RateRepository;
use Database\Seeders\CurrencySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RateRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $rateRepository;

    public function setUp(): void
    {
        parent::setUp();

        app(CurrencySeeder::class)->run();
        $this->rateRepository = new RateRepository(new Rate());

    }

    public function test_get_rate_method()
    {
        $rate = Rate::all()->first();
        $rateExcepted = $this->rateRepository->getRateRecord($rate->currency->id, $rate->sourceCurrency->id);

        $this->assertEquals($rate->id, $rateExcepted->id);
    }

    public function test_update_rate_record()
    {
        $rate = Rate::all()->first();
        $amount = 100;
        $rateExcepted = $this->rateRepository->updateRateRecord($rate->currency->id, $rate->sourceCurrency->id, $amount);

        $this->assertEquals($amount, $rateExcepted->price);
    }


}

