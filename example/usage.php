<?php
include __DIR__ . "/../vendor/autoload.php";

use G\Redis\Cache;
use Predis\Client;

$cache = new Cache(new Client(json_decode(file_get_contents(__DIR__ . '/conf.json'), true)));

$value = $cache->get("aaa.aaa.aaa", function () {
    return [
        'a' => 1,
    ];
});

$cache->delete("aaa.aaa.aaa");