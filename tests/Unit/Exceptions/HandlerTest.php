<?php

use App\Events\CollectExceptionEvent;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class HandlerTest extends TestCase
{
    protected $handler;

    protected function setUp()
    {
        parent::setUp();

        $this->handler = resolve(ExceptionHandler::class);
    }

    public function testReport()
    {
        Event::fake();

        $request = new Request('GET', 'test');
        $response = new Response(404);

        $mockException = \Mockery::mock('GuzzleHttp\Exception\RequestException',
            array('qoo exception message', $request, $response))->makePartial();
        $mockException
            ->shouldReceive('getMessage')
            ->withNoArgs()
            ->andReturn('qoo exception message');

        $this->handler->report($mockException);

        Event::assertDispatched(CollectExceptionEvent::class, function (CollectExceptionEvent $event) use ($mockException, $request, $response) {
            return $event->message === 'qoo exception message' &&
                is_string($event->happeningOn) &&
                $event->context['request'] === \GuzzleHttp\Psr7\str($request) &&
                $event->context['response'] === \GuzzleHttp\Psr7\str($response);
        });
    }
}