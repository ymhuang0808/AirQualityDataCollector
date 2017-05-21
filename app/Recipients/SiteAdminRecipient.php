<?php

namespace App\Recipients;


class SiteAdminRecipient extends AbstractRecipient
{
    public function __construct()
    {
        $this->email = config('aqdc.site_admin_email', null);
    }
}