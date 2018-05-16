<?php

namespace Tests\Feature\Api;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\AggregationMetricTableTestDataTrait;
use Tests\TestCase;

class AggregationMeasurementsApiControllerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use AggregationMetricTableTestDataTrait;

    protected function setUp()
    {
        parent::setUp();

        $this->setupAggregationMetricHourly();
    }

    public function testShow()
    {
        $uri = sprintf('/api/sites/1/aggregation_measurements?' .
            'start_datetime=%s&' .
            'end_datetime=%s&' .
            'period_type=0',
            urlencode('2018-01-05T00:00:00+0000'),
            urlencode('2018-01-05T23:59:59+0000'));

        $response = $this->json('GET', $uri);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregation_measurements' => [
                    '*' => [
                        'start_datetime',
                        'end_datetime',
                        'values'
                    ],
                ],
                'site',
            ])
            ->assertJson([
                'aggregation_measurements' => [
                    [
                        'start_datetime' => '2018-01-05T00:00:00+00:00',
                        'end_datetime' => '2018-01-05T00:59:59+00:00',
                        'values' => ['pm25' => 41, 'pm10' => 39],
                    ],
                ],
            ])
            ->assertJsonCount(24, 'aggregation_measurements');
    }
}