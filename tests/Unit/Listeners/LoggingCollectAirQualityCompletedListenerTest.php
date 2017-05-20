<?php

use App\Events\CollectAirQualityCompletedEvent;
use App\Listeners\LoggingCollectAirQualityCompletedListener;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Monolog\Logger;
use Tests\TestCase;

class LoggingCollectAirQualityCompletedListenerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $listener;

    protected $mockEvent;

    protected function setUp()
    {
        parent::setUp();

        $this->listener = resolve(LoggingCollectAirQualityCompletedListener::class);

        $this->makeMock();
    }

    public function testHandle()
    {
        $this->listener->handle($this->mockEvent);

        $this->assertDatabaseHas('collection_logs', [
            'level' => Logger::INFO,
            'channel' => 'collect-air-quality',
            'count' => 128,
            'message' => 'Collected 128 air quality dataset items from oaoa',
            'source_type' => 'oaoa',
        ]);
    }

    protected function makeMock()
    {
        $mockCollection = Mockery::mock(Collection::class);
        $mockCollection->shouldReceive('count')
            ->withNoArgs()
            ->andReturn(128);

        $this->mockEvent = new CollectAirQualityCompletedEvent($mockCollection, 'oaoa');
    }

    protected function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }
}