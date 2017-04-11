<?php

use App\Repository\SimpleArrayCacheRepository;
use Tests\TestCase;

class SimpleArrayCacheRepositoryTest extends TestCase
{
    protected $simpleArrayCacheRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->simpleArrayCacheRepository = new SimpleArrayCacheRepository();
    }

    public function testCacheAccess()
    {
        $fakeItem1 = 'FolllllooOL';
        $fakeItem2 = [
            'halo',
            'kerker',
        ];
        $this->simpleArrayCacheRepository->setItemByKey('thisIsaFoolkey', $fakeItem1);
        $this->simpleArrayCacheRepository->setItemByKey('AtEstingKey', $fakeItem2);

        $this->assertEquals('FolllllooOL', $this->simpleArrayCacheRepository->getItemByKey('thisIsaFoolkey'));
        $this->assertEquals(['halo', 'kerker'], $this->simpleArrayCacheRepository->getItemByKey('AtEstingKey'));
        $this->assertNull($this->simpleArrayCacheRepository->getItemByKey('nothingKeY'));

        $this->assertTrue($this->simpleArrayCacheRepository->isHit('thisIsaFoolkey'));
        $this->assertTrue($this->simpleArrayCacheRepository->isHit('AtEstingKey'));
        $this->assertFalse($this->simpleArrayCacheRepository->isHit('nothingKeY'));

        $this->assertContains('FolllllooOL', $this->simpleArrayCacheRepository->getItems());
        $this->assertContains(['halo', 'kerker'], $this->simpleArrayCacheRepository->getItems());
    }
}