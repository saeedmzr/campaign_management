<?php

namespace App\Services;

use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Objects\User;

class TelegramService
{
    protected $telegram;

    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * @throws TelegramSDKException
     */
    public function show()
    {
        $response = $this->telegram->getMe();

    }

    /**
     * @throws TelegramSDKException
     */
    public function forwardMessage($chatId, $fromChatId, $messageId)
    {
        $response = $this->telegram->forwardMessage([
            'chat_id' => $chatId,
            'from_chat_id' => $fromChatId,
            'message_id' => $messageId
        ]);
        return $response->getMessageId();
    }

    public function sendMessage($chatId, $message, $reply_markup = null)
    {
        $data = [
            "chat_id" => $chatId,
            "text" => $message,
        ];
        if ($reply_markup) {
            $data['reply_markup'] = $reply_markup;
        }
        $response = $this->telegram->sendMessage($data);
        return $response->getMessageId();
    }

    /**
     * @throws TelegramSDKException
     */
    public function sendPhoto($chatId, $path, $caption = null, $reply_markup = null)
    {
        $data = [
            "chat_id" => $chatId,
            "photo" => $path,
        ];
        if ($caption)
            $data['caption'] = $caption;
        if ($reply_markup)
            $data['reply_markup'] = $reply_markup;

        $response = $this->telegram->sendPhoto($data);

        return $response->getMessageId();
    }

    public function makeRowButtons($array): array
    {
        $row = [];
        foreach ($array as $item) {
            $row[] = Keyboard::button($item);
            $row[] = Keyboard::button($item);
            $row[] = Keyboard::button($item);
        }
        return $row;
    }

    public function makeKeyboardButtons($array): Keyboard
    {
        $replyMarkup = Keyboard::make()
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true);
        foreach ($array as $rowButtons) {
            $replyMarkup->row($rowButtons);
        }
        return $replyMarkup;
    }
}
