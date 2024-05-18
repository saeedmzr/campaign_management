<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class CrawlArtsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:arts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    function handle()
    {
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
                    $artist = \App\Models\Artist::updateOrCreate([
                        "artistId" => $item['artistId'],
                    ], [
                        "userId" => $item['createdByUserId'],
                    ]);
                }

                \App\Models\Art::updateOrCreate([
                    "artist_id" => $artist->id ?? null,
                    "submissionId" => $item['submissionId'],
                    "raw_id" => $item['id'],
                ], [
                    "name" => $item['name'],
                    "countryOfResidence" => $item['countryOfResidence'],
                    "title" => $item['title'],
                    "submission" => $item['submission'],
                    "openCallId" => $item['openCallId'],
                ]);
            }


        }

    }

}
