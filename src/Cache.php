<?php
namespace G\Redis;

use Predis\ClientInterface;

class Cache
{
    private $redis;

    public function __construct(ClientInterface $redis)
    {
        $this->redis = $redis;
    }

    public function get($key, callable $callback = null, $ttl = null)
    {
        if ($this->redis->exists($key)) {
            return json_decode($this->redis->get($key), true);
        }

        if (!is_callable($callback)) {
            throw new Exception("Key value '{$key}' not in cache and not available callback to populate cache");
        }

        $out = call_user_func($callback);
        if (!is_null($ttl)) {
            $this->redis->set($key, json_encode($out), 'EX', $ttl);
        } else {
            $this->redis->set($key, json_encode($out));
        }

        return $out;
    }

    public function delete($key)
    {
        $keys = $this->redis->keys($key);
        if (count($keys) > 0) {
            $this->redis->del($keys);
        }

        return $keys;
    }
}