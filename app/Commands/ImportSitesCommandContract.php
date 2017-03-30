<?php

namespace App\Commands;


use App\Repository\SitesRepositoryContract;

interface ImportSitesCommandContract
{
    public function execute();
}
