<?php

use App\Notifications\CollectExceptionNotification;
use Tests\Unit\Notifications\MockNotifiable;
use Tests\TestCase;

class CollectExceptionNotificationTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testVia()
    {
        $notification = new CollectExceptionNotification('test fake message', 'halo ha');

        $mockNotifiable = $this->getMockNotifialbe();

        $result = $notification->via($mockNotifiable);

        $this->assertEquals(['mail'], $result);
    }

    public function testToMailWithStringHappeningOn()
    {
        $notification = new CollectExceptionNotification('test fake message', 'halo ha');

        $mockNotifiable = $this->getMockNotifialbe();

        $result = $notification->toMail($mockNotifiable)->data();

        $this->assertEquals('error', $result['level']);
        $this->assertEquals('Collect exception occurs', $result['subject']);
        $this->assertNull($result['greeting']);
        $this->assertNull($result['salutation']);
        $this->assertEquals([
            'Air quality data collector encounters an exception, please see the following information:',
            'test fake message',
            'Happening on:',
            'halo ha',
        ], $result['introLines']);
    }

    protected function getMockNotifialbe()
    {
        $mockNotifiable = $this->getMockBuilder(MockNotifiable::class)->getMock();
        $mockNotifiable
            ->expects($this->any())
            ->method('routeNotificationFor')
            ->with($this->equalTo('mail'))
            ->willReturn('yes@airgoood.xx');

        return $mockNotifiable;
    }
}