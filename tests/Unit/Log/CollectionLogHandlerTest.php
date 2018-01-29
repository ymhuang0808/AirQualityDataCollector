<?php

use App\CollectionLog;
use App\Log\CollectionLogHandler;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Monolog\Logger;
use Tests\TestCase;

class CollectionLogHandlerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $handler;

    protected function setUp()
    {
        parent::setUp();

        $this->handler = new CollectionLogHandler();
    }

    public function testHandle()
    {
        $fakeRecord = [
            'message' => 'ker-ker-la!',
            'context' => array(
                'count' => 128,
                'source_type' => 'test_type',
            ),
            'level' => Logger::INFO,
            'level_name' => Logger::getLevelName(Logger::INFO),
            'channel' => 'haloha',
            'datetime' => new DateTimeImmutable('@0'),
            'extra' => [],
        ];

        $this->handler->handle($fakeRecord);

        $this->assertDatabaseHas('collection_logs', [
            'level' => Logger::INFO,
            'channel' => 'haloha',
            'count' => 128,
            'message' => 'ker-ker-la!',
            'source_type' => 'test_type',
        ]);
    }
}