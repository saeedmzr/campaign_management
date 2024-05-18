<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\BotController;
use App\Models\Currency;
use App\Models\Rate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateMessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:updates';

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
        $botController = app(BotController::class);
        $botController->getUpdates();
    }
}
