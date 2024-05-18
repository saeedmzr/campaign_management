<?php

namespace App\Telegram\Commands;

use App\Services\TelegramService;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Start Command to get you started';
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }


    public function handle()
    {
        $fallbackUser = $this->getUpdate()->getMessage()->from;
        $user = $this->telegramService->storeUser($fallbackUser);
        $text = "Hey $user->first_name,Welcome to our bot! ";
        $keyboardButtons = $this->telegramService->defaultKeyboards();
        $rowButtons = $this->telegramService->makeRowButtons($keyboardButtons);
        $keyboard = $this->telegramService->makeKeyboardButtons($rowButtons);
        $this->replyWithMessage([
            'text' => $text,
            'reply_markup' => $keyboard
        ]);
    }

}
