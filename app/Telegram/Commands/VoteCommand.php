<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class VoteCommand extends Command
{
    protected string $name = 'asdasdasd';
    protected string $description = 'Start Command to get you started';
//    protected string $pattern = 'like';

    public function handle()
    {
        $this->replyWithMessage([
            'text' => 'You can start voting now',
        ]);
    }

}
