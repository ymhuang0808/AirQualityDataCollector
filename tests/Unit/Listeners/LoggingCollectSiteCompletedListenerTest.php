<?php

use App\Listeners\LoggingCollectSiteCompletedListener;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Monolog\Logger;
use Tests\TestCase;

class LoggingCollectSiteCompletedListenerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $listener;

    protected $mockEvent;

    protected function setUp()
    {
        parent::setUp();

        $this->listener = resolve(LoggingCollectSiteCompletedListener::class);

        $this->makeMock();
    }

    public function testHandle()
    {
        $this->listener->handle($this->mockEvent);

        $this->assertDatabaseHas('collection_logs', [
            'level' => Logger::INFO,
            'channel' => 'collect-site',
            'count' => 78,
            'message' => 'Collected 78 sites from yooo',
            'source_type' => 'yooo',
        ]);
    }

    protected function makeMock()
    {
        $mockCollection = Mockery::mock(Collection::class);
        $mockCollection->shouldReceive('count')
            ->withNoArgs()
            ->andReturn(78);

        $this->mockEvent = new \App\Events\CollectSiteCompletedEvent($mockCollection, 'yooo');
    }
}