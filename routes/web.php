<?php


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

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


    try {
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
            return response(
                [
                    'search' => $response['searchAfter'],
                    'data' => $response['data']
                ]
            );


        } else {
            // Handle errors
            echo "Error: Status code " . $statusCode . "\n";
            echo $responseBody;
        }
    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage() . "\n";
    }
});

Route::get("/update", function () {
    $a = Telegram::getUpdates();
    $b = Telegram::getMe();
    return response($b);
});
