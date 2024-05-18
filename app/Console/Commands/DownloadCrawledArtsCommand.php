<?php

namespace App\Console\Commands;

use App\Models\Art;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadCrawledArtsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:arts';

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
        foreach (Art::where("image_path", null)->get() as $art) {

            $responsee = Http::get($art->submission['url600']);
            // Get the image content
            if ($responsee->successful()) {
                $imageContent = $responsee->body();

                $filename = time() . '_' . basename($art->submission['url600']);

                Storage::put('public/images/' . $filename, $imageContent);

                $art->update(
                    ["image_path" => $filename]
                );
            }
        }
    }
}
