<?php

namespace App\Providers;

use App\Repositories\ArtRepository;
use App\Repositories\VoteRepository;
use App\Services\RedisService;
use App\Services\TelegramService;
use App\Telegram\Commands\StartCommand;
use Illuminate\Support\ServiceProvider;
use Telegram\Bot\Api;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $telegramService = app(TelegramService::class);
        $artRepository = app(ArtRepository::class);
        $voteRepository = app(VoteRepository::class);
        $redisService = app(RedisService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
