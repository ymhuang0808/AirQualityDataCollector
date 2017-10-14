<?php

namespace Tests\Unit;

use App\Site;
use App\Transformers\EpaAirQualityTransformer;
use App\Transformers\RemoteModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EpaAirQualityTransformerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    protected $fakeAirQuality;

    protected function setUp()
    {
        parent::setUp();

        $this->fakeAirQuality = \Mockery::mock('stdClass');
        $this->fakeAirQuality->SiteName = '竹山';
        $this->fakeAirQuality->County = '南投縣';
        $this->fakeAirQuality->AQI = '31';
        $this->fakeAirQuality->Pollutant = '';
        $this->fakeAirQuality->Status = '良好';
        $this->fakeAirQuality->SO2 = '1.6';
        $this->fakeAirQuality->CO = '0.48';
        $this->fakeAirQuality->CO_8hr = '1.33';
        $this->fakeAirQuality->O3 = '26';
        $this->fakeAirQuality->O3_8hr = '31';
        $this->fakeAirQuality->PM10 = '48';
        $this->fakeAirQuality->PM10_AVG = '79';
        $this->fakeAirQuality->{'PM2.5'} = '31';
        $this->fakeAirQuality->{'PM2.5_AVG'} = 45;
        $this->fakeAirQuality->NO2 = '20';
        $this->fakeAirQuality->WindSpeed = '1.6';
        $this->fakeAirQuality->WindDirec = '32';
        $this->fakeAirQuality->NOx = '21.39';
        $this->fakeAirQuality->NO = '1.44';
        $this->fakeAirQuality->PublishTime = '2017-03-26 14:00';

        factory(Site::class)
            ->make([
                'name' => '竹山',
            ])
            ->save();
    }

    public function testTransform()
    {
        $transformer = new EpaAirQualityTransformer();
        $actualRemoteModel = $transformer->transform($this->fakeAirQuality);

        $this->assertInstanceOf(RemoteModel::class, $actualRemoteModel);
        $this->assertEquals(31, $actualRemoteModel->fields['aqi']);
        $this->assertEmpty($actualRemoteModel->fields['pollutant']);
        $this->assertEquals('良好', $actualRemoteModel->fields['status']);
        $this->assertEquals(1.6, $actualRemoteModel->fields['so2']);
        $this->assertEquals(0.48, $actualRemoteModel->fields['co']);
        $this->assertEquals(1.33, $actualRemoteModel->fields['co_8hr']);
        $this->assertEquals(26, $actualRemoteModel->fields['o3']);
        $this->assertEquals(31, $actualRemoteModel->fields['o3_8hr']);
        $this->assertEquals(48, $actualRemoteModel->fields['pm10']);
        $this->assertEquals(79, $actualRemoteModel->fields['pm10_avg']);
        $this->assertEquals(31, $actualRemoteModel->fields['pm25']);
        $this->assertEquals(45, $actualRemoteModel->fields['pm25_avg']);
        $this->assertEquals(20, $actualRemoteModel->fields['no2']);
        $this->assertEquals(1.6, $actualRemoteModel->fields['wind_speed']);
        $this->assertEquals(32, $actualRemoteModel->fields['wind_direction']);
        $this->assertEquals(21.39, $actualRemoteModel->fields['nox']);
        $this->assertEquals(1.44, $actualRemoteModel->fields['no']);
        $this->assertEquals('2017-03-26 14:00:00', $actualRemoteModel->fields['published_datetime']);
    }

    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
