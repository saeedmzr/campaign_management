<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Rate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $btc = Currency::query()->create(
            ["name" => "Bitcoin", "symbol" => "BTC"]
        );
        $tether = Currency::query()->create(
            ["name" => "Tether", "symbol" => "USDT"]
        );
        $irr = Currency::query()->create(
            ["name" => "Rial", "symbol" => "IRR"]
        );

        Rate::create([
            'currency_id' => $btc->id,
            'source_currency_id' => $tether->id,
            'price' => 1,

        ]);
        Rate::create([
            'currency_id' => $btc->id,
            'source_currency_id' => $irr->id,
            'price' => 1,
        ]);
        Rate::create([
            'currency_id' => $tether->id,
            'source_currency_id' => $irr->id,
            'price' => 1,

        ]);


    }
}
