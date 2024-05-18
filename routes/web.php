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
Route::get("/", function () {
    $client = new Client();
    $url = 'https://api.thehug.xyz/api/open-call/submissions';
    $headers = [
        'User-Agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:125.0) Gecko/20100101 Firefox/125.0',
        'Accept' => '*/*',
        'Accept-Language' => 'en-US,en;q=0.5',
        'Accept-Encoding' => 'gzip, deflate, br',
        'Referer' => 'https://thehug.xyz/',
        'Content-Type' => 'application/json',
        'hug-client-version' => 'e1874fa', // Assuming you have the actual version here
        'Authorization' => '', // Replace with your actual authorization token
        'Origin' => 'https://thehug.xyz',
        'Connection' => 'keep-alive',
        'Sec-Fetch-Dest' => 'empty',
        'Sec-Fetch-Mode' => 'cors',
        'Sec-Fetch-Site' => 'same-site',
        'TE' => 'trailers',
    ];

    $body = [
        'openCallId' => 'art-for-life',
        'selectedOnly' => false,
        'perPage' => 181,
        'sort' => ['title'],
    ];


    $response = $client->post($url, [
        'headers' => $headers,
        'json' => $body,
    ]);

    // Handle the response
    $statusCode = $response->getStatusCode();
    $responseBody = $response->getBody()->getContents();

    if ($statusCode >= 200 && $statusCode < 300) {
        // Success! Process the response data in $responseBody
        $response = json_decode($responseBody, true);
        foreach ($response['data'] as $item) {
            if (isset($item['artistId']) && $item['artistId']) {
                $artist = \App\Models\Artist::create([
                    "artistId" => $item['artistId'],
                    "userId" => $item['createdByUserId'],
                ]);
            }

            \App\Models\Art::create([
                "artist_id" => $artist->id ?? null,
                "raw_id" => $item['id'],
                "name" => $item['name'],
                "countryOfResidence" => $item['countryOfResidence'],
                "title" => $item['title'],
                "submission" => $item['submission'],
                "openCallId" => $item['openCallId'],
                "submissionId" => $item['submissionId'],
            ]);
        }


    }

});

Route::get("download", function () {
    foreach (Art::where("image_path", null)->get() as $art) {
//        $responsee = Http::get($art->submission['url150']);
        // Get the image content
//        if ($responsee->successful()) {
//            $imageContent = $responsee->body();

            // Generate a unique filename based on the current time and original name
            $filename = time() . '_' . basename($art->submission['url150']);

            // Store the image in the storage/app/public folder
//            Storage::put('public/images/' . $filename, $imageContent);

            $art->update(
                ["image_path" => $filename]
            );
//        }


    }
});
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


