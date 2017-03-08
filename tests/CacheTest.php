<?php
use Predis\Client;
use G\Redis\Cache;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{
    public function test_simple_get()
    {
        $redis = $this->getMockBuilder(Client::class)
            ->setMethods(['exists', 'set'])
            ->getMock();

        $redis->method('exists')->willReturn(false);
        $redis->expects($this->exactly(1))->method('set')->willReturn(true);

        $cache = new Cache($redis);
        $value = $cache->get("key", function () {
            return "Hello";
        });

        $this->assertEquals("Hello", $value);
    }

    public function test_from_cache()
    {
        $redis = $this->getMockBuilder(Client::class)
            ->setMethods(['exists', 'get'])
            ->getMock();

        $redis->expects($this->exactly(1))->method('exists')->willReturn(true);
        $redis->expects($this->exactly(1))->method('get')->willReturn(json_encode(['hello' => 'world']));

        $cache = new Cache($redis);
        $value = $cache->get("key");

        $this->assertEquals(['hello' => 'world'], $value);
    }

    /**
     * @expectedException G\Redis\Exception
     */
    public function test_does_not_exists_in_cache()
    {
        $redis = $this->getMockBuilder(Client::class)
            ->setMethods(['exists', 'get'])
            ->getMock();

        $redis->expects($this->exactly(1))->method('exists')->willReturn(false);

        $cache = new Cache($redis);
        $cache->get("key");
    }

    public function test_force_delete()
    {
        $redis = $this->getMockBuilder(Client::class)
            ->setMethods(['keys', 'del'])
            ->getMock();

        $redis->expects($this->exactly(1))->method('keys')->willReturn(["a", "b"]);
        $redis->expects($this->exactly(1))->method('del')->willReturn(["a", "b"]);

        $cache = new Cache($redis);
        $deletedKeys = $cache->delete("key");

        $this->assertEquals(["a", "b"], $deletedKeys);
    }
}