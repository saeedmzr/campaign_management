<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Models\Rate;
use App\Repositories\CurrencyRepository;
use App\Repositories\RateRepository;
use App\Services\CryptoService;
use App\Services\Exchanger\FCSApi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdatingRatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:updating-rates-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

    }
}
