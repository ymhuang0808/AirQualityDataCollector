<?php

use App\Events\AirQualityMeasurementAggregationCompleted;
use App\Listeners\LoggingAirQualityAggregationCompletedListener;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Monolog\Logger;

class LoggingAirQualityAggregationCompletedListenerTest extends \Tests\TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * @var \App\Listeners\LoggingAirQualityAggregationCompletedListener
     */
    protected $listener;

    protected function setUp()
    {
        parent::setUp();

        $this->listener = resolve(LoggingAirQualityAggregationCompletedListener::class);
    }

    public function testHandle()
    {
        $mockEvent = $this->makeMockEvent();
        $this->listener->handle($mockEvent);

        $this->assertDatabaseHas('aggregation_logs', [
            'aggregation_type' => 'hourly',
            'source_type' => 'my_source',
            'start_datetime' => '2017-10-21 15:31:00',
            'end_datetime' => '2017-10-22 15:31:00',
            'message' => 'Aggregation completed',
            'level' => Logger::INFO,
        ]);
    }

    protected function makeMockEvent()
    {
        $mockEvent = Mockery::mock(AirQualityMeasurementAggregationCompleted::class);
        $mockEvent->shouldReceive('getAggregationType')
            ->withNoArgs()
            ->andReturn('hourly');
        $mockEvent->shouldReceive('getSourceType')
            ->withNoArgs()
            ->andReturn('my_source');
        $mockEvent->shouldReceive('getStartDateTime')
            ->withNoArgs()
            ->andReturn(\Carbon\Carbon::createSafe(2017, 10, 21, 15, 31, 0));
        $mockEvent->shouldReceive('getEndDateTime')
            ->withNoArgs()
            ->andReturn(\Carbon\Carbon::createSafe(2017, 10, 22, 15,31, 0));

        return $mockEvent;
    }

    protected function tearDown()
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
    }
}