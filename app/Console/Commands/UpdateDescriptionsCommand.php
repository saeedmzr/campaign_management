<?php

namespace App\Console\Commands;

use App\Models\Art;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class UpdateDescriptionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:descriptions';

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
        $client = new Client();

        foreach (Art::where("description", null)->get() as $art) {
            $url = "https://api.thehug.xyz/api/open-call/submission/art-for-life/$art->submissionId";
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
            $response = $client->get($url, [
                'headers' => $headers,
            ]);

            // Handle the response
            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();

            if ($statusCode >= 200 && $statusCode < 300) {
                // Success! Process the response data in $responseBody
                $response = json_decode($responseBody, true);
                $desc = $response['opencall']['voterSubmissionPromptResponse'];
                $art->update([
                    "description" => $desc,
                ]);

            }

        }

    }
}
