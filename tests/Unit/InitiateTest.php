<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Requests\FindOrderRequest;
use App\Http\Requests\GetRateRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\RateResource;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Rate;
use Database\Factories\OrderFactory;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InitiateTest extends TestCase
{
    use RefreshDatabase;


    public function test_models_exists()
    {
        $this->assertTrue(class_exists(Order::class));
        $this->assertTrue(class_exists(Currency::class));
        $this->assertTrue(class_exists(Rate::class));
    }


    public function test_migrations_exists()
    {
        $this->assertFileExists('database/migrations/2024_04_15_174002_create_currencies_table.php');
        $this->assertFileExists('database/migrations/2024_04_15_174014_create_rates_table.php');
        $this->assertFileExists('database/migrations/2024_04_15_174015_create_orders_table.php');
    }



    public function test_factory_exists()
    {
        $this->assertTrue(class_exists(OrderFactory::class));
    }


    public function test_seeder_exists()
    {
        $this->assertTrue(class_exists(CurrencySeeder::class));
        $this->assertTrue(class_exists(DatabaseSeeder::class));
    }



    public function test_routes_exists()
    {
        $this->assertFileExists('routes/api.php');

    }



    public function test_controller_exists()
    {
        $this->assertTrue(class_exists(BaseController::class));
        $this->assertTrue(class_exists(OrderController::class));
        $this->assertTrue(class_exists(CurrencyController::class));
    }


    public function test_requests_and_resources_exists()
    {
        $this->assertTrue(class_exists(StoreOrderRequest::class));
        $this->assertTrue(class_exists(FindOrderRequest::class));
        $this->assertTrue(class_exists(GetRateRequest::class));
        $this->assertTrue(class_exists(RateResource::class));
        $this->assertTrue(class_exists(OrderResource::class));
        $this->assertTrue(class_exists(CurrencyResource::class));
    }


    public function test_controller_methods_exists()
    {
        $this->assertTrue(method_exists(BaseController::class, 'successResponse'));
        $this->assertTrue(method_exists(BaseController::class, 'errorResponse'));
        $this->assertTrue(method_exists(OrderController::class, 'show'));
        $this->assertTrue(method_exists(OrderController::class, 'store'));
        $this->assertTrue(method_exists(CurrencyController::class, 'index'));
        $this->assertTrue(method_exists(CurrencyController::class, 'rate'));
    }

}
