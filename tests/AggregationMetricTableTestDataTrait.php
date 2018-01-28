<?php

namespace Tests;


trait AggregationMetricTableTestDataTrait
{
    protected function setupAggregationMetricHourly()
    {
        /** @var \App\Site $fakeSite1 */
        $fakeSite1 = factory(\App\Site::class)->create([
            'name' => 'airbox_test_site_01',
            'source_type' => 'airbox',
        ]);
        /** @var \App\Site $fakeSite2 */
        $fakeSite2 = factory(\App\Site::class)->create([
            'name' => 'airbox_test_site_02',
            'source_type' => 'airbox',
        ]);

        $fakeAggregationMetrics = [
            // Site1
            [
                'start_datetime' => '2018-01-04 23:00:00',
                'end_datetime' => '2018-01-04 23:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 46, 'pm10' => 49],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 00:00:00',
                'end_datetime' => '2018-01-05 00:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 41, 'pm10' => 39],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 01:00:00',
                'end_datetime' => '2018-01-05 01:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 37, 'pm10' => 38],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 02:00:00',
                'end_datetime' => '2018-01-05 02:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 37, 'pm10' => 41],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 03:00:00',
                'end_datetime' => '2018-01-05 03:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 43, 'pm10' => 37],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 04:00:00',
                'end_datetime' => '2018-01-05 04:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 41, 'pm10' => 44],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 05:00:00',
                'end_datetime' => '2018-01-05 05:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 34, 'pm10' => 41],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 06:00:00',
                'end_datetime' => '2018-01-05 06:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 44, 'pm10' => 53],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 07:00:00',
                'end_datetime' => '2018-01-05 07:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 43, 'pm10' => 51],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 08:00:00',
                'end_datetime' => '2018-01-05 08:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 39, 'pm10' => 44],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 09:00:00',
                'end_datetime' => '2018-01-05 09:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 39, 'pm10' => 44],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 10:00:00',
                'end_datetime' => '2018-01-05 10:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 41, 'pm10' => 49],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 11:00:00',
                'end_datetime' => '2018-01-05 11:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 45, 'pm10' => 53],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 12:00:00',
                'end_datetime' => '2018-01-05 12:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 43, 'pm10' => 56],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 13:00:00',
                'end_datetime' => '2018-01-05 13:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 31, 'pm10' => 39],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 14:00:00',
                'end_datetime' => '2018-01-05 14:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 31, 'pm10' => 40],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 15:00:00',
                'end_datetime' => '2018-01-05 15:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 32, 'pm10' => 39],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 16:00:00',
                'end_datetime' => '2018-01-05 16:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 31, 'pm10' => 40],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 17:00:00',
                'end_datetime' => '2018-01-05 17:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 29, 'pm10' => 34],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 18:00:00',
                'end_datetime' => '2018-01-05 18:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 22, 'pm10' => 21],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 19:00:00',
                'end_datetime' => '2018-01-05 19:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 25, 'pm10' => 23],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 20:00:00',
                'end_datetime' => '2018-01-05 20:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 28, 'pm10' => 31],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 21:00:00',
                'end_datetime' => '2018-01-05 21:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 26, 'pm10' => 29],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 22:00:00',
                'end_datetime' => '2018-01-05 22:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 11, 'pm10' => 8],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 23:00:00',
                'end_datetime' => '2018-01-05 23:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 14, 'pm10' => 11],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-06 00:00:00',
                'end_datetime' => '2018-01-06 00:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 9, 'pm10' => 10],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-06 01:00:00',
                'end_datetime' => '2018-01-06 01:59:59',
                'site_id' => $fakeSite1->getKey(),
                'values' => ['pm25' => 10, 'pm10' => 11],
                'period_type' => 0,
            ],
            //
            // Site2
            //
            [
                'start_datetime' => '2018-01-04 23:00:00',
                'end_datetime' => '2018-01-04 23:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 11, 'pm10' => 9],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 00:00:00',
                'end_datetime' => '2018-01-05 00:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 23, 'pm10' => 39],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 01:00:00',
                'end_datetime' => '2018-01-05 01:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 39, 'pm10' => 31],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 02:00:00',
                'end_datetime' => '2018-01-05 02:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 39, 'pm10' => 33],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 03:00:00',
                'end_datetime' => '2018-01-05 03:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 49, 'pm10' => 43],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 04:00:00',
                'end_datetime' => '2018-01-05 04:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 44, 'pm10' => 51],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 05:00:00',
                'end_datetime' => '2018-01-05 05:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 49, 'pm10' => 48],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 06:00:00',
                'end_datetime' => '2018-01-05 06:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 51, 'pm10' => 55],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 07:00:00',
                'end_datetime' => '2018-01-05 07:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 59, 'pm10' => 51],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 08:00:00',
                'end_datetime' => '2018-01-05 08:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 60, 'pm10' => 59],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 09:00:00',
                'end_datetime' => '2018-01-05 09:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 58, 'pm10' => 59],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 10:00:00',
                'end_datetime' => '2018-01-05 10:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 65, 'pm10' => 61],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 11:00:00',
                'end_datetime' => '2018-01-05 11:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 69, 'pm10' => 65],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 12:00:00',
                'end_datetime' => '2018-01-05 12:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 71, 'pm10' => 69],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 13:00:00',
                'end_datetime' => '2018-01-05 13:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 67, 'pm10' => 59],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 14:00:00',
                'end_datetime' => '2018-01-05 14:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 69, 'pm10' => 55],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 15:00:00',
                'end_datetime' => '2018-01-05 15:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 81, 'pm10' => 75],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 16:00:00',
                'end_datetime' => '2018-01-05 16:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 84, 'pm10' => 80],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 17:00:00',
                'end_datetime' => '2018-01-05 17:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 88, 'pm10' => 73],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 18:00:00',
                'end_datetime' => '2018-01-05 18:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 85, 'pm10' => 81],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 19:00:00',
                'end_datetime' => '2018-01-05 19:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 84, 'pm10' => 79],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 20:00:00',
                'end_datetime' => '2018-01-05 20:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 89, 'pm10' => 71],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 21:00:00',
                'end_datetime' => '2018-01-05 21:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 91, 'pm10' => 79],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 22:00:00',
                'end_datetime' => '2018-01-05 22:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 89, 'pm10' => 80],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-05 23:00:00',
                'end_datetime' => '2018-01-05 23:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 95, 'pm10' => 78],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-06 00:00:00',
                'end_datetime' => '2018-01-06 00:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 45, 'pm10' => 30],
                'period_type' => 0,
            ],
            [
                'start_datetime' => '2018-01-06 01:00:00',
                'end_datetime' => '2018-01-06 01:59:59',
                'site_id' => $fakeSite2->getKey(),
                'values' => ['pm25' => 31, 'pm10' => 20],
                'period_type' => 0,
            ],
        ];

        foreach ($fakeAggregationMetrics as $item) {
            factory(\App\AggregationMetric::class)->create($item);
        }
    }
}