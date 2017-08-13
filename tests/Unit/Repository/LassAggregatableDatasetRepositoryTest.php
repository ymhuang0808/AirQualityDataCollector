<?php

namespace tests\Unit\Repository;

use App\Repository\LassAggregatableDatasetRepository;
use App\Site;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LassAggregatableDatasetRepositoryTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @var  \App\Repository\LassAggregatableDatasetRepository $repository */
    protected $repository;

    protected function setUp()
    {
        parent::setUp();
        $this->setUpLassDatabase();

        $this->repository = new LassAggregatableDatasetRepository();
    }

    public function testGetSiteIdAndMinDatetimeSincePublishedDatetime()
    {
        $actual = $this->repository->getSiteIdAndMinDatetimeSincePublishedDatetime('2017-07-23 09:50:00');

        $this->assertCount(2, $actual);

        $this->assertObjectHasAttribute('min_datetime', $actual[0]);
        $this->assertObjectHasAttribute('site_id', $actual[0]);
        $this->assertObjectHasAttribute('min_datetime', $actual[1]);
        $this->assertObjectHasAttribute('site_id', $actual[1]);

        $this->assertEquals('2017-07-23 09:50:47', $actual[0]->min_datetime);
        $this->assertEquals(1, $actual[0]->site_id);
        $this->assertEquals('2017-07-23 10:29:01', $actual[1]->min_datetime);
        $this->assertEquals(2, $actual[1]->site_id);
    }

    public function testGetAvgFieldBetweenPublishedDatetime()
    {
        $siteId1AvgPm25Value = $this->repository->getAvgFieldBetweenPublishedDatetime('pm25', '2017-07-23 09:00:00', '2017-07-23 12:00:00');
        $siteId1AvgPm10Value = $this->repository->getAvgFieldBetweenPublishedDatetime('pm10', '2017-07-23 09:00:00', '2017-07-23 12:00:00');

        $this->assertEquals(9.9, $siteId1AvgPm25Value);
        $this->assertEquals(6.875, $siteId1AvgPm10Value);

    }

    protected function setUpLassDatabase()
    {
        /** @var \App\Site $fakeSite1 */
        $fakeSite1 = factory(\App\Site::class)->create();
        /** @var \App\Site $fakeSite2 */
        $fakeSite2 = factory(\App\Site::class)->create();

        $fakeDataset = [
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
                'pm25' => 33.2,
                'pm10' => 28.7,
                'temperature' => 27.9,
                'humidity' => 38.2,
                'site_id' => $fakeSite1->getKey(),
                'published_datetime' => '2017-07-23 12:13:07',
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
                'pm25' => 49.27,
                'pm10' => 33.28,
                'temperature' => 29.1,
                'humidity' => 36.2,
                'site_id' => $fakeSite2->getKey(),
                'published_datetime' => '2017-07-23 11:11:33',
            ],

        ];

        foreach ($fakeDataset as $item) {
            factory(\App\LassDataset::class)->create($item);
        }
    }
}