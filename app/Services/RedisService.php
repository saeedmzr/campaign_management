<?php

namespace App\Services;

use Redis;
use RedisException;

class RedisService
{
    private $redis;
    private $otp_expiry;

    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect(getenv("REDIS_HOST", "127.0.0.1"), getenv("REDIS_PORT", "6379"));
    }

    /**
     * @throws RedisException
     */
    public function getKeysFromRedis($key = "*")
    {
        return $this->redis->keys($key);
    }

    public function storeMessage($userId, $firstArt, $secondArt)
    {

        $key = "compare-$userId";
        $this->redis->hSet($key, 'first_art', $firstArt);
        $this->redis->hSet($key, 'second_art', $secondArt);
        $this->redis->expire($key, 100000);
        return true;
    }

    public function getMessageFromRedis($key)
    {
        if ($this->redis->exists($key)) {
            return [
                "first_art" => $this->redis->hGet($key, 'first_art'),
                "second_art" => $this->redis->hGet($key, 'second_art'),
            ];
        }
        return null;
    }

    public function deleteMessageFromRedis($key)
    {
        if ($this->redis->exists($key)) {
            $this->redis->del($key);
        }
    }
}
