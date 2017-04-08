<?php

use App\County;
use App\Township;
use App\Transformers\EpaSiteTransformer;
use App\Transformers\RemoteModel;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class EpaSiteTransformerTest extends TestCase
{
    use DatabaseMigrations;

    protected $fakeSite;

    protected function setUp()
    {
        parent::setUp();
        $this->fakeSite = Mockery::mock('stdClass');
        $this->fakeSite->SiteName = '竹山';
        $this->fakeSite->SiteEngName = 'Zhushan';
        $this->fakeSite->AreaName = '中部空品區';
        $this->fakeSite->County = '南投縣';
        $this->fakeSite->Township = '竹山鎮';
        $this->fakeSite->SiteAddress = '南投縣竹山鎮大明路666號';
        $this->fakeSite->TWD97Lon = 120.6773060000;
        $this->fakeSite->TWD97Lat = 23.7563890000;
        $this->fakeSite->SiteType = '一般測站';
    }
    
    public function testTransform()
    {
        $transformer = new EpaSiteTransformer();
        $actualRemoteModel = $transformer->transform($this->fakeSite);

        $this->assertInstanceOf(RemoteModel::class, $actualRemoteModel);

        $this->assertEquals('竹山', $actualRemoteModel->fields['name']);
        $this->assertEquals('Zhushan', $actualRemoteModel->fields['eng_name']);
        $this->assertEquals('中部空品區', $actualRemoteModel->fields['area_name']);
        $this->assertEquals('南投縣竹山鎮大明路666號', $actualRemoteModel->fields['address']);
        $this->assertEquals('一般測站', $actualRemoteModel->fields['type']);
        $this->assertEquals('epa', $actualRemoteModel->fields['source_type']);
        $this->assertEquals(120.6773060000, $actualRemoteModel->fields['coordinates']['longitude']);
        $this->assertEquals(23.7563890000, $actualRemoteModel->fields['coordinates']['latitude']);

        $this->assertInstanceOf(County::class, $actualRemoteModel->relationships['county']);
        $this->assertInstanceOf(Township::class, $actualRemoteModel->relationships['township']);
    }
}