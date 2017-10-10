<?php

return [
    'site_admin' => [
        'email' => env('SITE_ADMIN_EMAIL', null),
        'pushbullet_email' => env('SITE_ADMIN_EMAIL', null),
    ],

    'notification' => [
        'exception_frequency' => env('NOTIFICATION_EXCEPTION_FREQUENCY', 1800),
    ],

    'remote_source' => [
        'epa' => [
            'base_url' => 'http://opendata.epa.gov.tw',
            'air_quality_uri' => '/webapi/api/rest/datastore/355000000I-000001?format=json',
            'site_uri' => '/webapi/api/rest/datastore/355000000I-000005?format=jsonp',
        ],
        'lass' => [
            'base_url' => 'https://pm25.lass-net.org',
            'air_quality_uri' => '/data/last-all-lass.json',
            'site_uri' => '/data/last-all-lass.json',
        ],
        'airbox' => [
            'base_url' => 'https://pm25.lass-net.org',
            'air_quality_uri' => '/data/last-all-airbox.json',
            'site_uri' => '/data/last-all-airbox.json',
        ],
    ],
];