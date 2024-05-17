<?php

use App\Console\Commands\UpdatingRatesCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Schedule::command(UpdatingRatesCommand::class)->everyMinute();
