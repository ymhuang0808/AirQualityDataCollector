<?php

namespace Test\Unit\Repository;


use App\AggregationLog;
use App\Repository\AggregationLogRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\AggregationLogTableTestDataTrait;
use Tests\TestCase;

class AggregationLogRepositoryTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use AggregationLogTableTestDataTrait;

    /**
     * @var AggregationLogRepository
     */
    protected $repository;

    protected function setUp()
    {
        parent::setUp();

        $aggregationLogs = $this->generateDailyLassAggregationLogs();

        foreach ($aggregationLogs as $aggregationLog) {
            factory(AggregationLog::class)->create($aggregationLog);
        }

        $this->repository = new AggregationLogRepository();
    }

    public function testGetBeginDatetime()
    {
        $lastExecuteDatetime = Carbon::createSafe(2017, 10, 23, 23, 59, 59);
        $result = $this
            ->repository
            ->getBeginDatetime($lastExecuteDatetime, 'lass');

        $expected = Carbon::create(2017, 10, 29, 23, 59, 59);
        $this->assertTrue($result->eq($expected));
    }
}
