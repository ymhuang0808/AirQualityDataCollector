<?php

namespace Tests;


Trait AggregatableTestDataTrait
{
    protected function setUpLassDatabase()
    {
        /** @var \App\Site $fakeSite1 */
        $fakeSite1 = factory(\App\Site::class)->create([
            'source_type' => 'lass',
        ]);
        /** @var \App\Site $fakeSite2 */
        $fakeSite2 = factory(\App\Site::class)->create([
            'source_type' => 'lass',
        ]);

        $fakeDataset = [
            // Site 1
            [
                'pm25' => 10.1,
                'pm10' => 9.3,
                'temperature' => 23.5,
                'humidity' => 89.1,
                'site_id' => $fakeSite1->getKey(),
                'published_datetime' => '2017-07-22 23:59:59',
            ],
            [
                'pm25' => 8.3,
                'pm10' => 5.1,
                'temperature' => 25,
                'humidity' => 41.2,
                'site_id' => $fakeSite1->getKey(),
                'published_datetime' => '2017-07-23 09:13:10',
            ],
            [
                'pm25' => 9.9,
                'pm10' => 6.2,
                'temperature' => 25.4,
                'humidity' => 40.3,
                'site_id' => $fakeSite1->getKey(),
                'published_datetime' => '2017-07-23 09:22:38',
            ],
            [
                'pm25' => 11.3,
                'pm10' => 9.1,
                'temperature' => 27.1,
                'humidity' => 44.8,
                'site_id' => $fakeSite1->getKey(),
                'published_datetime' => '2017-07-23 09:50:47',
            ],
            [
                'pm25' => 10.1,
                'pm10' => 7.1,
                'temperature' => 27.3,
                'humidity' => 45.2,
                'site_id' => $fakeSite1->getKey(),
                'published_datetime' => '2017-07-23 10:12:22',
            ],
            [
                'pm25' => 21.9,
                'pm10' => 11.3,
                'temperature' => 27.8,
                'humidity' => 46.2,
                'site_id' => $fakeSite1->getKey(),
                'published_datetime' => '2017-07-23 10:43:35',
            ],
            [
                'pm25' => 33.2,
                'pm10' => 28.7,
                'temperature' => 27.9,
                'humidity' => 38.2,
                'site_id' => $fakeSite1->getKey(),
                'published_datetime' => '2017-07-23 12:13:07',
            ],
            [
                'pm25' => 45.9,
                'pm10' => 38.9,
                'temperature' => 25.3,
                'humidity' => 87.8,
                'site_id' => $fakeSite1->getKey(),
                'published_datetime' => '2017-07-23 20:31:33',
            ],
            [
                'pm25' => 34.5,
                'pm10' => 22.8,
                'temperature' => 24.1,
                'humidity' => 88.7,
                'site_id' => $fakeSite1->getKey(),
                'published_datetime' => '2017-07-24 00:00:01',
            ],
            [
                'pm25' => 35.2,
                'pm10' => 21.6,
                'temperature' => 23.0,
                'humidity' => 89.9,
                'site_id' => $fakeSite1->getKey(),
                'published_datetime' => '2017-07-24 00:05:12',
            ],
            // Site 2
            [
                'pm25' => 32.81,
                'pm10' => 23.19,
                'temperature' => 23.1,
                'humidity' => 76.66,
                'site_id' => $fakeSite2->getKey(),
                'published_datetime' => '2017-07-22 22:47:31',
            ],
            [
                'pm25' => 33.41,
                'pm10' => 24.89,
                'temperature' => 27.8,
                'humidity' => 33.66,
                'site_id' => $fakeSite2->getKey(),
                'published_datetime' => '2017-07-23 09:49:59',
            ],
            [
                'pm25' => 41.83,
                'pm10' => 29.33,
                'temperature' => 28.1,
                'humidity' => 37.5,
                'site_id' => $fakeSite2->getKey(),
                'published_datetime' => '2017-07-23 10:29:01',
            ],
            [
                'pm25' => 40.23,
                'pm10' => 28.16,
                'temperature' => 29.1,
                'humidity' => 36.2,
                'site_id' => $fakeSite2->getKey(),
                'published_datetime' => '2017-07-23 10:57:47',
            ],
            [
                'pm25' => 49.27,
                'pm10' => 33.28,
                'temperature' => 29.1,
                'humidity' => 33.1,
                'site_id' => $fakeSite2->getKey(),
                'published_datetime' => '2017-07-23 11:11:33',
            ],
            [
                'pm25' => 50.21,
                'pm10' => 34.83,
                'temperature' => 30.03,
                'humidity' => 34.2,
                'site_id' => $fakeSite2->getKey(),
                'published_datetime' => '2017-07-23 11:49:58',
            ],
            [
                'pm25' => 43.22,
                'pm10' => 38.71,
                'temperature' => 29.09,
                'humidity' => 48.11,
                'site_id' => $fakeSite2->getKey(),
                'published_datetime' => '2017-07-23 17:21:31',
            ],
            [
                'pm25' => 49.27,
                'pm10' => 29.17,
                'temperature' => 22.53,
                'humidity' => 59.34,
                'site_id' => $fakeSite2->getKey(),
                'published_datetime' => '2017-07-23 23:59:59',
            ],
            [
                'pm25' => 44.1,
                'pm10' => 28.78,
                'temperature' => 20.37,
                'humidity' => 60.41,
                'site_id' => $fakeSite2->getKey(),
                'published_datetime' => '2017-07-24 00:19:18',
            ],
        ];

        foreach ($fakeDataset as $item) {
            factory(\App\LassDataset::class)->create($item);
        }
    }

    protected function setUpEpaDatabase()
    {
        /** @var \App\Site $fakeSite1 */
        $fakeSite1 = factory(\App\Site::class)->create([
            'name' => 'site1',
            'source_type' => 'epa',
        ]);
        /** @var \App\Site $fakeSite2 */
        $fakeSite2 = factory(\App\Site::class)->create([
            'name' => 'site2',
            'source_type' => 'epa',
        ]);

        $fakeDataset = [
            // Site 1
            [
                "aqi" => 6,
                "so2" => "2.8",
                "co" => 8,
                "o3" => 7,
                "co_8hr" => 6,
                "o3_8hr" => 8,
                "pm10" => 35,
                "pm25" => 41,
                "pm10_avg" => 32,
                "pm25_avg" => 41,
                "no2" => 16,
                "wind_speed" => "1.3",
                "wind_direction" => 5,
                "nox" => "5.3",
                "no" => "8.2",
                "pollutant" => "nec, euismod in, dolor.",
                "status" => "Aliquam tincidunt, nunc ac mattis ornare, lectus ante dictum mi,",
                "site_id" => $fakeSite1->getKey(),
                "published_datetime" => "2017-09-01 03:54:09"
            ],
            [
                "aqi" => 1,
                "so2" => "6.7",
                "co" => 1,
                "o3" => 5,
                "co_8hr" => 2,
                "o3_8hr" => 6,
                "pm10" => 23,
                "pm25" => 114,
                "pm10_avg" => 16,
                "pm25_avg" => 99,
                "no2" => 27,
                "wind_speed" => "7.0",
                "wind_direction" => 2,
                "nox" => "0.1",
                "no" => "3.1",
                "pollutant" => "dictum sapien. Aenean massa. Integer vitae nibh. Donec est mauris,",
                "status" => "elit. Nulla facilisi. Sed neque. Sed",
                "site_id" => $fakeSite1->getKey(),
                "published_datetime" => "2017-09-02 00:00:00"
            ],
            [
                "aqi" => 4,
                "so2" => "10.6",
                "co" => 2,
                "o3" => 5,
                "co_8hr" => 1,
                "o3_8hr" => 4,
                "pm10" => 49,
                "pm25" => 45,
                "pm10_avg" => 44,
                "pm25_avg" => 41,
                "no2" => 10,
                "wind_speed" => "69.3",
                "wind_direction" => 3,
                "nox" => "7.3",
                "no" => "1.3",
                "pollutant" => "et netus",
                "status" => "egestas",
                "site_id" => $fakeSite1->getKey(),
                "published_datetime" => "2017-09-02 03:33:03"
            ],
            [
                "aqi" => 5,
                "so2" => "5.8",
                "co" => 3,
                "o3" => 8,
                "co_8hr" => 3,
                "o3_8hr" => 7,
                "pm10" => 41,
                "pm25" => 51,
                "pm10_avg" => 44,
                "pm25_avg" => 38,
                "no2" => 31,
                "wind_speed" => "96.2",
                "wind_direction" => 1,
                "nox" => "3.9",
                "no" => "0.9",
                "pollutant" => "lorem. Donec elementum, lorem ut aliquam iaculis, lacus pede sagittis",
                "status" => "facilisis, magna",
                "site_id" => $fakeSite1->getKey(),
                "published_datetime" => "2017-09-02 07:10:48"
            ],
            [
                "aqi" => 9,
                "so2" => "9.9",
                "co" => 7,
                "o3" => 7,
                "co_8hr" => 2,
                "o3_8hr" => 3,
                "pm10" => 92,
                "pm25" => 76,
                "pm10_avg" => 81,
                "pm25_avg" => 56,
                "no2" => 1,
                "wind_speed" => "11.6",
                "wind_direction" => 1,
                "nox" => "8.8",
                "no" => "7.4",
                "pollutant" => "venenatis",
                "status" => "sem magna nec quam.",
                "site_id" => $fakeSite1->getKey(),
                "published_datetime" => "2017-09-02 07:51:52"
            ],
            [
                "aqi" => 10,
                "so2" => "8.2",
                "co" => 1,
                "o3" => 7,
                "co_8hr" => 3,
                "o3_8hr" => 6,
                "pm10" => 112,
                "pm25" => 19,
                "pm10_avg" => 87,
                "pm25_avg" => 19,
                "no2" => 59,
                "wind_speed" => "12.8",
                "wind_direction" => 6,
                "nox" => "18.2",
                "no" => "32.0",
                "pollutant" => "felis orci, adipiscing non, luctus sit amet, faucibus ut, nulla.",
                "status" => "est. Mauris eu turpis. Nulla aliquet. Proin velit.",
                "site_id" => $fakeSite1->getKey(),
                "published_datetime" => "2017-09-02 08:00:00"
            ],
            [
                "aqi" => 9,
                "so2" => "2.3",
                "co" => 9,
                "o3" => 2,
                "co_8hr" => 6,
                "o3_8hr" => 4,
                "pm10" => 78,
                "pm25" => 28,
                "pm10_avg" => 89,
                "pm25_avg" => 31,
                "no2" => 27,
                "wind_speed" => "97.9",
                "wind_direction" => 2,
                "nox" => "34.6",
                "no" => "12.3",
                "pollutant" => "ligula. Aenean euismod",
                "status" => "magna. Praesent interdum ligula eu enim. Etiam imperdiet",
                "site_id" => $fakeSite1->getKey(),
                "published_datetime" => "2017-09-02 13:48:45"
            ],
            [
                "aqi" => 17,
                "so2" => "7.8",
                "co" => 6,
                "o3" => 2,
                "co_8hr" => 5,
                "o3_8hr" => 2,
                "pm10" => 15,
                "pm25" => 67,
                "pm10_avg" => 11,
                "pm25_avg" => 64,
                "no2" => 19,
                "wind_speed" => "32.1",
                "wind_direction" => 2,
                "nox" => "32.2",
                "no" => "5.2",
                "pollutant" => "et magnis dis parturient montes, nascetur ridiculus mus. Proin vel",
                "status" => "augue ac ipsum. Phasellus vitae mauris sit amet lorem",
                "site_id" => $fakeSite1->getKey(),
                "published_datetime" => "2017-09-02 13:48:50"
            ],
            [
                "aqi" => 16,
                "so2" => "901.0",
                "co" => 9,
                "o3" => 9,
                "co_8hr" => 7,
                "o3_8hr" => 8,
                "pm10" => 92,
                "pm25" => 24,
                "pm10_avg" => 83,
                "pm25_avg" => 25,
                "no2" => 49,
                "wind_speed" => "177.6",
                "wind_direction" => 8,
                "nox" => "518.8",
                "no" => "19.7",
                "pollutant" => "dignissim tempor arcu. Vestibulum",
                "status" => "tortor. Nunc commodo",
                "site_id" => $fakeSite1->getKey(),
                "published_datetime" => "2017-09-03 05:29:51"
            ],
            [
                "aqi" => 9,
                "so2" => "5.4",
                "co" => 1,
                "o3" => 8,
                "co_8hr" => 3,
                "o3_8hr" => 8,
                "pm10" => 88,
                "pm25" => 95,
                "pm10_avg" => 63,
                "pm25_avg" => 87,
                "no2" => 40,
                "wind_speed" => "8.8",
                "wind_direction" => 5,
                "nox" => "4.0",
                "no" => "51.9",
                "pollutant" => "nunc nulla vulputate dui, nec tempus mauris",
                "status" => "magnis dis parturient",
                "site_id" => $fakeSite1->getKey(),
                "published_datetime" => "2017-09-03 07:10:13"
            ],
            // Site 2
            [
                "aqi" => 5,
                "so2" => "7.9",
                "co" => 3,
                "o3" => 4,
                "co_8hr" => 0.3,
                "o3_8hr" => 8,
                "pm10" => 37,
                "pm25" => 78,
                "pm10_avg" => 33,
                "pm25_avg" => 63,
                "no2" => 28,
                "wind_speed" => "8.1",
                "wind_direction" => 3,
                "nox" => ".6",
                "no" => "10.2",
                "pollutant" => "feugiat. Sed nec metus facilisis lorem",
                "status" => "lectus convallis est,",
                "site_id" => $fakeSite2->getKey(),
                "published_datetime" => "2017-09-01 22:23:12"
            ],
            [
                "aqi" => 1,
                "so2" => "661.1",
                "co" => 1,
                "o3" => 1,
                "co_8hr" => 3,
                "o3_8hr" => 4,
                "pm10" => 11,
                "pm25" => 64,
                "pm10_avg" => 12,
                "pm25_avg" => 55,
                "no2" => 44,
                "wind_speed" => "5520.7",
                "wind_direction" => 4,
                "nox" => "790.1",
                "no" => "225.9",
                "pollutant" => "lacinia. Sed congue,",
                "status" => "vitae dolor. Donec fringilla. Donec feugiat metus",
                "site_id" => $fakeSite2->getKey(),
                "published_datetime" => "2017-09-02 13:10:13"
            ],
            [
                "aqi" => 15,
                "so2" => "5.7",
                "co" => 2,
                "o3" => 4,
                "co_8hr" => 2,
                "o3_8hr" => 4,
                "pm10" => 98,
                "pm25" => 68,
                "pm10_avg" => 88,
                "pm25_avg" => 61,
                "no2" => 60,
                "wind_speed" => "6.4",
                "wind_direction" => 5,
                "nox" => "7.1",
                "no" => "4.1",
                "pollutant" => "eget, venenatis a, magna. Lorem ipsum dolor sit amet, consectetuer",
                "status" => "malesuada vel, venenatis vel,",
                "site_id" => $fakeSite2->getKey(),
                "published_datetime" => "2017-09-02 13:56:53"
            ],
            [
                "aqi" => 5,
                "so2" => "6.0",
                "co" => 1,
                "o3" => 0,
                "co_8hr" => 0.3,
                "o3_8hr" => 0,
                "pm10" => 76,
                "pm25" => 110,
                "pm10_avg" => 56,
                "pm25_avg" => 98,
                "no2" => 26,
                "wind_speed" => "2.3",
                "wind_direction" => 8,
                "nox" => "6.7",
                "no" => "64.6",
                "pollutant" => "urna suscipit nonummy. Fusce fermentum fermentum arcu. Vestibulum ante ipsum",
                "status" => "porttitor scelerisque",
                "site_id" => $fakeSite2->getKey(),
                "published_datetime" => "2017-09-02 15:30:09"
            ],
            [
                "aqi" => 9,
                "so2" => "32.3",
                "co" => 9,
                "o3" => 6,
                "co_8hr" => 3,
                "o3_8hr" => 5,
                "pm10" => 97,
                "pm25" => 30,
                "pm10_avg" => 88,
                "pm25_avg" => 31,
                "no2" => 3,
                "wind_speed" => "3.2",
                "wind_direction" => 5,
                "nox" => "5.3",
                "no" => "22.7",
                "pollutant" => "eu dui.",
                "status" => "Nullam ut nisi a odio semper cursus. Integer mollis.",
                "site_id" => $fakeSite2->getKey(),
                "published_datetime" => "2017-09-02 19:45:05"
            ],
            [
                "aqi" => 17,
                "so2" => "3.5",
                "co" => 3,
                "o3" => 9,
                "co_8hr" => 4,
                "o3_8hr" => 3,
                "pm10" => 38,
                "pm25" => 83,
                "pm10_avg" => 33,
                "pm25_avg" => 84,
                "no2" => 34,
                "wind_speed" => "63.9",
                "wind_direction" => 9,
                "nox" => "43.6",
                "no" => "69.5",
                "pollutant" => "pede nec ante blandit viverra. Donec tempus, lorem fringilla ornare",
                "status" => "urna, nec luctus felis purus",
                "site_id" => $fakeSite2->getKey(),
                "published_datetime" => "2017-09-02 19:46:24"
            ],
            [
                "aqi" => 1,
                "so2" => "1.2",
                "co" => 8,
                "o3" => 9,
                "co_8hr" => 6,
                "o3_8hr" => 9,
                "pm10" => 26,
                "pm25" => 35,
                "pm10_avg" => 21,
                "pm25_avg" => 33,
                "no2" => 47,
                "wind_speed" => "43.7",
                "wind_direction" => 4,
                "nox" => "29.6",
                "no" => "7.9",
                "pollutant" => "magna a tortor. Nunc commodo auctor velit. Aliquam",
                "status" => "sodales",
                "site_id" => $fakeSite2->getKey(),
                "published_datetime" => "2017-09-02 20:43:31"
            ],
            [
                "aqi" => 10,
                "so2" => "13.2",
                "co" => 4,
                "o3" => 1,
                "co_8hr" => 3,
                "o3_8hr" => 2,
                "pm10" => 94,
                "pm25" => 47,
                "pm10_avg" => 88,
                "pm25_avg" => 45,
                "no2" => 54,
                "wind_speed" => "13.3",
                "wind_direction" => 6,
                "nox" => "66.6",
                "no" => "5.6",
                "pollutant" => "purus. Duis elementum, dui quis accumsan convallis,",
                "status" => "montes, nascetur ridiculus mus. Donec dignissim magna a tortor. Nunc",
                "site_id" => $fakeSite2->getKey(),
                "published_datetime" => "2017-09-02 20:47:31"
            ],
            [
                "aqi" => 7,
                "so2" => "4.3",
                "co" => 8,
                "o3" => 0,
                "co_8hr" => 6,
                "o3_8hr" => 2,
                "pm10" => 93,
                "pm25" => 56,
                "pm10_avg" => 63,
                "pm25_avg" => 56,
                "no2" => 44,
                "wind_speed" => "94.4",
                "wind_direction" => 3,
                "nox" => "41.4",
                "no" => "6.4",
                "pollutant" => "amet ultricies sem magna nec",
                "status" => "consectetuer adipiscing elit. Etiam laoreet, libero",
                "site_id" => $fakeSite2->getKey(),
                "published_datetime" => "2017-09-02 23:59:59"
            ],
            [
                "aqi" => 12,
                "so2" => "6.0",
                "co" => 5,
                "o3" => 5,
                "co_8hr" => 3,
                "o3_8hr" => 4,
                "pm10" => 49,
                "pm25" => 112,
                "pm10_avg" => 44,
                "pm25_avg" => 83,
                "no2" => 58,
                "wind_speed" => "75.4",
                "wind_direction" => 0,
                "nox" => "88.5",
                "no" => "49.9",
                "pollutant" => "amet, faucibus ut, nulla. Cras eu tellus eu augue porttitor",
                "status" => "diam. Pellentesque habitant morbi tristique senectus et netus et malesuada",
                "site_id" => $fakeSite2->getKey(),
                "published_datetime" => "2017-09-03 10:14:36"
            ],
            [
                "aqi" => 4,
                "so2" => "87.5",
                "co" => 9,
                "o3" => 2,
                "co_8hr" => 5,
                "o3_8hr" => 2,
                "pm10" => 37,
                "pm25" => 36,
                "pm10_avg" => 33,
                "pm25_avg" => 33,
                "no2" => 4,
                "wind_speed" => "91.4",
                "wind_direction" => 2,
                "nox" => "19.0",
                "no" => "13.0",
                "pollutant" => "nec tempus",
                "status" => "sagittis",
                "site_id" => $fakeSite2->getKey(),
                "published_datetime" => "2017-09-03 19:44:33"
            ],

        ];

        foreach ($fakeDataset as $item) {
            factory(\App\EpaDataset::class)->create($item);
        }
    }
}