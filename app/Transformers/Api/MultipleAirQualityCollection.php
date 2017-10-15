<?php

namespace App\Transformers\Api;


use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Resource\Collection as ResourceCollection;
use League\Fractal\TransformerAbstract;
use Traversable;

class MultipleAirQualityCollection implements MultipleAirQualityContract, \IteratorAggregate
{
    protected $collection = [];

    protected $manager;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->manager->setSerializer(new ArraySerializer());
    }

    public function add(string $sourceType, Collection $dataCollection, TransformerAbstract $transformer)
    {
        $resource = new ResourceCollection($dataCollection, $transformer);
        $result = $this->manager->createData($resource)->toArray();
        $this->collection[$sourceType] = $result['data'];
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator([$this->collection]);
    }

    public function all(): array
    {
        return $this->collection;
    }
}