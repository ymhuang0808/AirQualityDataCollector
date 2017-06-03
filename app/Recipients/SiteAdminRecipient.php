<?php

namespace App\Recipients;

use NotificationChannels\Pushbullet\Targets\Email as PushbulletEmail;

class SiteAdminRecipient extends AbstractRecipient
{
    public function __construct()
    {
        $this->email = config('aqdc.site_admin.email', null);
        $this->pushbulletEmail = config('aqdc.site_admin.pushbullet_email', null);
    }

    public function routeNotificationForPushbullet()
    {
        return new PushbulletEmail($this->pushbulletEmail);
    }
}