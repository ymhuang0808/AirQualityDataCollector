<?php

namespace App\Recipients;


use Illuminate\Notifications\Notifiable;

abstract class AbstractRecipient
{
    use Notifiable;

    protected $email;

    public function getKey()
    {
        return 'email';
    }
}