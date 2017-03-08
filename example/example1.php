<?php

include __DIR__ . "/../vendor/autoload.php";

use G\Redis\Cache;
use Predis\Client;

class CacheBuilder
{
    public static function get($key, callable $callback = null, $ttl = null)
    {
        return self::getCache()->get($key, $callback, $ttl);
    }

    public static function delete($key)
    {
        return self::getCache()->delete($key);
    }

    private static function getCache()
    {
        return new Cache(new Client(json_decode(file_get_contents(__DIR__ . '/conf.json'), true)));
    }
}

print_r(CacheBuilder::get("aaa.aaa.aaa", function () {
    return [
        'a' => 1,
    ];
}));

print_r(CacheBuilder::get("aaa.aaa.aaa"));
print_r(CacheBuilder::delete("aaa.aaa.*"));
try {
    CacheBuilder::get("aaa.aaa.aaa");
} catch (\Exception $e) {
    print_r($e->getMessage());
}