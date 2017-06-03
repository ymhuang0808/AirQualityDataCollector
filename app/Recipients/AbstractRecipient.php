<?php

namespace App\Recipients;


use Illuminate\Notifications\Notifiable;

abstract class AbstractRecipient
{
    use Notifiable;

    protected $email;

    protected $pushbulletEmail;

    public function getKey()
    {
        return 'email';
    }
}