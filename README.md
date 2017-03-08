PHP Redis cache wrapper
======
[![Build Status](https://travis-ci.org/gonzalo123/redisphpwrapper.svg?branch=master)](https://travis-ci.org/gonzalo123/redisphpwrapper)

Simple cache wrapper build in top of Predis/Redis

usage:
```php
use G\Redis\Cache;
use Predis\Client;

$cache = new Cache(new Client(json_decode(file_get_contents(__DIR__ . '/conf.json'), true)));

$value = $cache->get("aaa.aaa.aaa", function () {
    return [
        'a' => 1,
    ];
});

$cache->delete("aaa.aaa.aaa");
```