<?php

use App\Events\CollectExceptionEvent;
use App\Listeners\CollectExceptionNotificationListener;
use App\Notifications\CollectExceptionNotification;
use App\Recipients\SiteAdminRecipient;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CollectExceptionNotificationListenerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $mockRecipient;
    protected $listener;

    protected function setUp()
    {
        parent::setUp();
        $this->mockRecipient = new SiteAdminRecipient();
        $this->listener = new CollectExceptionNotificationListener($this->mockRecipient);
    }

    public function testHandle()
    {
        $mockEvent = new CollectExceptionEvent(
            'thisIsTestingMsg',
            'In abc.php code',
            ['context 0001', 'context 0x2']);

        Notification::fake();

        Cache::shouldReceive('has')
            ->once()
            ->with('notification-handle:c2d9ba5cbd063fb42ff0585193b605d07e7feddd')
            ->andReturn(false);

        Cache::shouldReceive('put')
            ->once()
            ->withArgs(['notification-handle:c2d9ba5cbd063fb42ff0585193b605d07e7feddd', 1, 1800]);

        $this->listener->handle($mockEvent);

        Notification::assertSentTo(
            $this->mockRecipient,
            CollectExceptionNotification::class,
            function (CollectExceptionNotification $notification, $channels) {
                return $notification->message === 'thisIsTestingMsg' &&
                    $notification->happeningOn === 'In abc.php code' &&
                    $notification->context === ['context 0001', 'context 0x2'];
            });
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}