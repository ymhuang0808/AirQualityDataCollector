<?php

namespace App\Transformers\Api;


use Illuminate\Database\Eloquent\Collection;
use League\Fractal\TransformerAbstract;

interface MultipleAirQualityContract
{
    public function all(): array ;
    public function add(string $sourceType, Collection $collection, TransformerAbstract $transformer);
}