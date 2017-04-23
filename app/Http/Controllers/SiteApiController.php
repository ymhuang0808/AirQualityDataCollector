<?php

namespace App\Http\Controllers;


use App\Site;

class SiteApiController extends Controller
{
    public function getAll()
    {
        $sites = Site::all();


    }
}