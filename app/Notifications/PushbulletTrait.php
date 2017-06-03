<?php
/**
 * Created by PhpStorm.
 * User: aming
 * Date: 2017/5/30
 * Time: 上午11:07
 */

namespace App\Notifications;


trait PushbulletTrait
{
    public function isPushbulletAvailable()
    {
        return !empty(config('services.pushbullet.access_token')) &&
            !empty(config('aqdc.site_admin.pushbullet_email'));
    }
}