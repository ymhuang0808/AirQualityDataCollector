<?php

use App\Transformers\LassSiteTransformer;
use App\Transformers\RemoteModel;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LassSiteTransformerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    protected $fakeSite;

    protected function setUp()
    {
        parent::setUp();

        $this->fakeSite = Mockery::mock('stdClass');
        $this->fakeSite->gps_num = 13;
        $this->fakeSite->app = 'PM25';
        $this->fakeSite->s_d1 = 24;
        $this->fakeSite->date = '2017-04-08';
        $this->fakeSite->s_d0 = 23;
        $this->fakeSite->gps_alt = 182;
        $this->fakeSite->s_h0 = 78.5;
        $this->fakeSite->gps_fix = 1;
        $this->fakeSite->ver_app = '0.8.3';
        $this->fakeSite->device_id = 'FT1_928';
        $this->fakeSite->gps_lat = 23.767234;
        $this->fakeSite->s_t0 = 28.6;
        $this->fakeSite->timestamp = '2017-04-08T07:33:44Z';
        $this->fakeSite->gps_lon = 120.7062;
        $this->fakeSite->devcie = 'LinkItONE';
        $this->fakeSite->tick = 601973761;
        $this->fakeSite->s_4 = 46.00;
        $this->fakeSite->s_1 = 100;
        $this->fakeSite->s_0 = 19692;
        $this->fakeSite->s_3 = 0;
        $this->fakeSite->s_2 = 1;
        $this->fakeSite->ver_format = 3;
        $this->fakeSite->FAKE_GPS = 0;
        $this->fakeSite->time = '07:33:44';
    }

    public function testTransform()
    {
        $transformer = new LassSiteTransformer();
        $actualRemoteModel = $transformer->transform($this->fakeSite);

        $this->assertInstanceOf(RemoteModel::class, $actualRemoteModel);

        $this->assertEquals('FT1_928', $actualRemoteModel->fields['name']);
        $this->assertEquals(23.767234, $actualRemoteModel->fields['coordinates']['latitude']);
        $this->assertEquals(120.7062, $actualRemoteModel->fields['coordinates']['longitude']);
        $this->assertEquals('lass', $actualRemoteModel->fields['source_type']);

        $this->assertCount(3, $actualRemoteModel->fields);

        $this->assertNull($actualRemoteModel->relationships);
    }

    protected function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }
}