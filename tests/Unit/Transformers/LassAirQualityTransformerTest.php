<?php

use App\Site;
use App\Transformers\LassAirQualityTransformer;
use App\Transformers\RemoteModel;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LassAirQualityTransformerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    protected $fakeAirQuality;

    protected function setUp()
    {
        parent::setUp();

        $this->fakeAirQuality = Mockery::mock('stdClass');
        $this->fakeAirQuality->gps_num = 13;
        $this->fakeAirQuality->app = 'PM25';
        $this->fakeAirQuality->s_d1 = 24;
        $this->fakeAirQuality->date = '2017-04-08';
        $this->fakeAirQuality->s_d0 = 23;
        $this->fakeAirQuality->gps_alt = 182;
        $this->fakeAirQuality->s_h0 = 78.5;
        $this->fakeAirQuality->gps_fix = 1;
        $this->fakeAirQuality->ver_app = '0.8.3';
        $this->fakeAirQuality->device_id = 'FT1_928';
        $this->fakeAirQuality->gps_lat = 23.767234;
        $this->fakeAirQuality->s_t0 = 28.6;
        $this->fakeAirQuality->timestamp = '2017-04-08T07:33:44Z';
        $this->fakeAirQuality->gps_lon = 120.7062;
        $this->fakeAirQuality->devcie = 'LinkItONE';
        $this->fakeAirQuality->tick = 601973761;
        $this->fakeAirQuality->s_4 = 46.00;
        $this->fakeAirQuality->s_1 = 100;
        $this->fakeAirQuality->s_0 = 19692;
        $this->fakeAirQuality->s_3 = 0;
        $this->fakeAirQuality->s_2 = 1;
        $this->fakeAirQuality->ver_format = 3;
        $this->fakeAirQuality->FAKE_GPS = 0;
        $this->fakeAirQuality->time = '07:33:44';

        factory(Site::class)
            ->make([
                'name' => 'FT1_928',
                'source_type' => 'lass',
            ])
            ->save();
    }

    public function testTransform()
    {
        $transformer = new LassAirQualityTransformer();
        $actualRemoteModel = $transformer->transform($this->fakeAirQuality);


        $this->assertInstanceOf(RemoteModel::class, $actualRemoteModel);
        $this->assertEquals(23, $actualRemoteModel->fields['pm25']);
        $this->assertEquals(24, $actualRemoteModel->fields['pm10']);
        $this->assertEquals(28.6, $actualRemoteModel->fields['temperature']);
        $this->assertEquals(78.5, $actualRemoteModel->fields['humidity']);
        $this->assertEquals('2017-04-08 07:33:44', $actualRemoteModel->fields['published_datetime']);
        $this->assertCount(5, $actualRemoteModel->fields);

        $actualRelatedSite = $actualRemoteModel->relationships['site'];

        $this->assertEquals('FT1_928', $actualRelatedSite->name);
    }

    protected function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

}