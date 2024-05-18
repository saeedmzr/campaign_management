<?php

namespace App\Http\Controllers\Api;

use App\Repositories\ArtRepository;
use App\Repositories\UserRepository;
use App\Repositories\VoteRepository;
use App\Services\RedisService;
use App\Services\TelegramService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\User;

class BotController extends BaseController
{
    private $telegramService;
    private $artRepository;
    private $voteRepository;
    private $redisService;
    private $userRepository;

    public function __construct(
        TelegramService $telegramService,
        ArtRepository   $artRepository,
        VoteRepository  $voteRepository,
        RedisService    $redisService,
        UserRepository  $userRepository,
    )
    {
        $this->telegramService = $telegramService;
        $this->artRepository = $artRepository;
        $this->voteRepository = $voteRepository;
        $this->redisService = $redisService;
        $this->userRepository = $userRepository;
    }

    public function getUpdates()
    {
        Telegram::commandsHandler(false);

        $update = Telegram::getUpdates(["allowed_updates" => ["message", "callback_query"]]);
        if (isset($update[0]['callback_query'])) {
            Log::error("khodafez");
            $this->likeHandler($update[0]['callback_query']);

        } else {
            if (!empty($update)) {
                Log::error("salam");
                $chatId = $update[0]['message']['from']['id'];
                $this->sendPhoto($chatId);
            }

        }
    }

    public function webhook()
    {
        Log::error("salm man omadam");
        Telegram::commandsHandler(true);

        $update = Telegram::getWebhookUpdate();
        if (isset($update['callback_query'])) {
            Log::error("khodafez");
            Log::error(json_encode($update));
            Log::error("khodafez");
            $this->likeHandler($update['callback_query']);

        } else {
            if (!empty($update)) {
                Log::error("salam");
                $chatId = $update['message']['from']['id'];
                $this->sendPhoto($chatId);
            }

        }
    }

    public function likeHandler($callbackQuery)
    {
        $id = $callbackQuery['id'];
        $message = $callbackQuery["message"];
        $mesageId = $message['message_id'];
        $from = $callbackQuery['from'];
        $data = $callbackQuery['data'];

        $likedOne = substr($data, 4, 2);

        $user = $this->userRepository->findByChatId($from['id']);
        $compare = $this->redisService->getMessageFromRedis("compare-$user->id");

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
            $this->voteRepository->updateLikes($user->id, $likedId, $didntLikeId);
            $this->redisService->deleteMessageFromRedis("compare-$user->id");
            $this->sendPhoto($user->chat_id);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
        }

    }

    public function sendPhoto($chatId)
    {
        $user = $this->userRepository->findByChatId($chatId);
        $userId = $user->id;
        $likedArt = $this->voteRepository->getArtIdThatUserLiked($userId);
        $doesntLikeArts = $this->voteRepository->getArtsThatUserDidntLike($userId);
        $getTwo = $this->artRepository->getCompareTwoArts($likedArt, $doesntLikeArts);
        Log::error(json_encode($getTwo));
        if (!$getTwo) {
            $art = $this->artRepository->findById($likedArt);
            return $this->telegramService->sendPhoto($chatId, storage_path('app/public/images/' . $art->image_path),
                "You choose $art->title as the best submission. Thank you.");
        }


        $this->redisService->storeMessage($userId, $getTwo["first_id"], $getTwo["second_id"]);
        $buttons = [];
        foreach ($getTwo as $artId) {
            $art = $this->artRepository->findById($artId);
            $buttons[] = ["text" => "$art->name", "data" => "like$art->id"];

            $this->telegramService->sendPhoto($chatId, storage_path('app/public/images/' . $art->image_path), $art->name);
        }
        $text = "Which one is better?";
        $keyboard = $this->telegramService->makeInlineKeyboard($buttons);
        return $this->telegramService->sendMessage($chatId, $text, $keyboard);

    }

}
