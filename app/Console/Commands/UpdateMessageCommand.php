<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\BotController;
use Illuminate\Console\Command;

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
