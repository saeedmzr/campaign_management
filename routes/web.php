<?php


use App\Models\Art;
use App\Models\Vote;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::get("send", [\App\Http\Controllers\Api\BotController::class, "sendPhoto"]);
Route::get("sendMessage", function () {
    $a = new \App\Services\TelegramService();
    $a->sendMessage("58360854", "hello");
});

Route::get("set", function () {
    Telegram::setWebhook([
        "url" => "https://bot.dadpardaz.com/api/webhook"
    ]);
});
Route::post("webhook", [\App\Http\Controllers\Api\BotController::class, "webhook"]);


Route::get("/update", function () {
//    $telegram = new \App\Services\TelegramService(
//        new \App\Repositories\ArtRepository(new \App\Models\Art()),
//        new \App\Repositories\VoteRepository(new \App\Models\Vote()),
//        new \App\Services\RedisService(),
//    );
    $update = Telegram::getUpdates(["allowed_updates" => ["message", "callback_query"]]);
//
//    if (isset($update[0]['callback_query']))
//        $telegram->likeHandler($update[0]['callback_query']);
//    $a = Telegram::commandsHandler(false);
    return response($update);
});

Route::get('setWebhook', function () {
    $response = Telegram::setWebhook(['url' => "https://backend-developer.ir/webhook"]);
});
Route::post("webhook", function () {
    $update = Telegram::commandsHandler(true);

});
Route::get("insert_fake", function () {
    $arts = Art::all();
    foreach ($arts as $art) {
        $vote =
            Vote::query()->updateOrCreate([
                "user_id" => 1,
                "art_id" => $art->id,
            ], [
                "is_voted" => 1,
                "is_liked" => 0,
            ]);
    }
});


