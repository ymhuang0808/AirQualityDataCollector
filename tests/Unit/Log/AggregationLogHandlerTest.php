<?php

use App\AggregationLog;
use App\Log\AggregationLogHandler;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Monolog\Logger;
use Tests\TestCase;

class AggregationLogHandlerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $handler;

    protected function setUp()
    {
        parent::setUp();

        $this->handler = new AggregationLogHandler();
    }

    public function testHandle()
    {
        $fakeRecord = [
            'message' => 'wahaha !',
            'context' => array(
                'aggregation_type' => 'my_type',
                'source_type' => 'test_type',
                'start_datetime' => \Carbon\Carbon::createSafe(2017, 10, 21, 11, 20, 30),
                'end_datetime' => \Carbon\Carbon::createSafe(2017, 10, 22, 11, 20, 30),
            ),
            'level' => Logger::INFO,
            'level_name' => Logger::getLevelName(Logger::INFO),
            'channel' => 'haloha',
            'datetime' => new DateTimeImmutable('@0'),
            'extra' => [],
        ];

        $this->handler->handle($fakeRecord);

        $this->assertDatabaseHas('aggregation_logs', [
            'level' => Logger::INFO,
            'aggregation_type' => 'my_type',
            'source_type' => 'test_type',
            'message' => 'wahaha !',
            'start_datetime' => '2017-10-21 11:20:30',
            'end_datetime' => '2017-10-22 11:20:30',
        ]);
    }
}