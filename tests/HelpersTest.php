<?php

namespace Test;

use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;
use Webpatser\Uuid\Uuid;

class HelpersTest extends TestCase
{
    protected $compressJson = [
        'status' => 200,
        'message' => 'success',
        'data' => [],
    ];
    protected $bytes = 10240;
    protected $cache = ['HelperTest', 'generateCacheKeyName', 'test', 'key'];
    protected $id = 987654321;
    protected $base62 = 'KHc6iHtXW3iD';
    protected $uuid = '4be0643f-1d98-573b-97cd-ca98a65347dd';
    protected $password = 'test';

    /** @test */
    public function compressJson()
    {
        $this->assertObjectHasAttribute('zip_json_key', json_decode(compressJson($this->compressJson)));
    }

    /** @test */
    public function extractJson()
    {
        $data = extractJson(compressJson($this->compressJson));
        $this->assertArrayHasKey('status', $data);
    }

    /** @test */
    public function formatBytes()
    {
        $formatBytes = formatBytes($this->bytes);
        $this->assertSame('10 kB', $formatBytes);
    }

    /** @test */
    public function generateCacheKeyName()
    {
        $this->assertSame('HelperTest:generateCacheKeyName:test:key',
            generateCacheKeyName($this->cache));
    }

    /** @test
     * @throws \Exception
     */
    public function carbon()
    {
        $this->assertInstanceOf(Carbon::class, carbon());
        $this->assertEquals(Carbon::parse('Jan 1 2017'), carbon('Jan 1 2017'));
    }

    /** @test */
    public function base62()
    {
        $this->assertSame($this->base62, base62()->encode($this->id));
        $this->assertSame($this->id, (int) base62()->decode(base62()->encode($this->id)));
    }

    /** @test */
    public function uuid()
    {
        $this->assertIsString(uuid(5, 'test', Uuid::NS_DNS)->string);
        $this->assertSame($this->uuid, uuid(5, 'test', Uuid::NS_DNS)->string);
    }

    /** @test */
    public function getSecret()
    {
        $secrets = getSecret(getcwd().'/tests/secrets');
        $this->assertIsObject($secrets);
        $this->assertIsArray($secrets->readAll());
        $this->assertSame($this->password, $secrets->readAll()['password']);
    }
}
