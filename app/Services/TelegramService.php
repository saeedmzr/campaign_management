<?php

namespace App\Services;

use App\Models\User;
use App\Models\Vote;
use App\Repositories\ArtRepository;
use App\Repositories\VoteRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\InputMedia\InputMediaPhoto;

class TelegramService
{

    private $artRepository;
    private $voteRepository;
    private $redisService;

    public function __construct(
        ArtRepository  $artRepository,
        VoteRepository $voteRepository,
        RedisService   $redisService,
    )
    {
        $this->artRepository = $artRepository;
        $this->voteRepository = $voteRepository;
        $this->redisService = $redisService;
    }

    /**
     * @throws TelegramSDKException
     */
    public function show()
    {
        $response = Telegram::getLastResponse();
    }

    public function storeUser($telegramUser)
    {
        $user = User::where('chat_id', $telegramUser->id)->first();
        if (!$user) {
            $payload = [
                "chat_id" => $telegramUser->id,
                "first_name" => $telegramUser->first_name,
                "last_name" => $telegramUser->last_name,
                "username" => $telegramUser->username,
            ];
            $user = User::create($payload);
        }
        return $user;

    }

    /**
     * @throws TelegramSDKException
     */
    public function forwardMessage($chatId, $fromChatId, $messageId)
    {
        $response = Telegram::forwardMessage([
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

        $response = Telegram::sendMessage($data);
        return $response->getMessageId();
    }

    /**
     * @throws TelegramSDKException
     */
    public function sendPhoto($chatId, $path, $caption = null, $reply_markup = null)
    {
        $data = [
            "chat_id" => $chatId,
            "photo" => InputFile::create($path, $path),

        ];
        if ($caption)
            $data['caption'] = $caption;
        if ($reply_markup)
            $data['reply_markup'] = $reply_markup;

        $response = Telegram::sendPhoto($data);

        return $response->getMessageId();
    }

    public function sendMediaGroup($chatId, $medias, $caption = null, $reply_markup = null)
    {
        foreach ($medias as $media) {
            $media = InputMediaPhoto::make()
                ->media(InputFile::create($media));
        }
        $data = [
            "chat_id" => $chatId,
            "media" => $medias,
        ];
        if ($caption)
            $data['caption'] = $caption;
        if ($reply_markup)
            $data['reply_markup'] = $reply_markup;

        $response = Telegram::sendMediaGroup($data);
        return $response->getMessageId();
    }

    public function makeRowButtons($array): array
    {
        $final = [];
        foreach ($array as $row) {
            $final_row = [];
            foreach ($row as $cell) {
                $final_row[] = Keyboard::button($cell);
            }
            $final[] = $final_row;
        }
        return $final;
    }

    public function makePhotoKeyboard($rows)
    {

        $keyboard = Keyboard::make()
            ->inline();
        foreach ($rows as $row) {
            $finalRow = [];
            foreach ($row as $button) {
                $finalRow[] = Keyboard::inlineButton(['text' => $button['text'], 'url' => $button['url']]);

            }
            $keyboard->row($finalRow);
        }

        return $keyboard;
    }

    public function makeInlineKeyboard($rows)
    {

        $keyboard = Keyboard::make()
            ->inline();
        foreach ($rows as $row) {
            $finalRow = [];
            foreach ($row as $button) {
                $finalRow[] = Keyboard::inlineButton(['text' => $button['text'], 'callback_data' => $button['data']]);

            }
            $keyboard->row($finalRow);
        }

        return $keyboard;
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

    public function defaultKeyboards()
    {
        $array = [
            ["vote" => "/vote"],
            ["/help", "/login"]
        ];
        return $array;
    }

    public function likeHandler($callbackQuery)
    {
        $redis = new RedisService();
        $id = $callbackQuery['id'];
        $message = $callbackQuery["message"];
        $mesageId = $message['message_id'];
        $from = $callbackQuery['from'];
        $chatId = $from['id'];
        $data = $callbackQuery['data'];
        $likedOne = substr($data, 4, 2);
        $user = User::where("chat_id", $chatId)->first();
        $compare = $redis->getMessageFromRedis("compare-$user->id");
        $firstArtId = $compare['first_art'];
        $secondArt = $compare['second_art'];

        if ($firstArtId == $likedOne) {
            $didntLikeId = $secondArt;
            $likedId = $firstArtId;
        } else {
            $didntLikeId = $firstArtId;
            $likedId = $secondArt;
        }
        try {
            DB::beginTransaction();
            $user->votes()->where("art_id", $likedId)->delete();
            $user->votes()->where("is_liked", true)->delete();
            $user->votes()->where("art_id", $didntLikeId)->delete();

            $user->votes()->create([
                "art_id" => $likedId,
                "is_voted" => true,
                "is_liked" => true,
            ]);
            $user->votes()->create([
                "art_id" => $didntLikeId,
                "is_voted" => true,
                "is_liked" => false,
            ]);

            $redis->deleteMessageFromRedis("compare-$user->id");
            $this->sendNextComapre($user->id,);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
        }


    }

    public function sendNextComapre($userId)
    {
        $user = User::find($userId);
        $chatId = $user->chat_id;
        $userId = $user->id;
        $likedArt = $this->voteRepository->getArtIdThatUserLiked($userId);
        $doesntLikeArts = $this->voteRepository->getArtsThatUserDidntLike($userId);
        $getTwo = $this->artRepository->getCompareTwoArts($likedArt, $doesntLikeArts);

        $this->redisService->storeMessage($userId, $getTwo["first_id"], $getTwo["second_id"]);
        $this->sendMessage($chatId, "Select wich one do you like?");
        $buttons = [];
        foreach ($getTwo as $artId) {
            $art = $this->artRepository->findById($artId);
            $buttons[] = ["text" => "$art->name", "data" => "like$art->id"];

            $this->sendPhoto($chatId, $art->submission['url150'], $art->name);
        }
        $text = "Which one is better?";
        $keyboard = $this->makeInlineKeyboard($buttons);
        $this->sendMessage($chatId, $text, $keyboard);

    }

}
