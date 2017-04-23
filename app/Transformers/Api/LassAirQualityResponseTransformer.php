<?php

namespace App\Transformers\Api;


use App\LassDataset;
use League\Fractal\TransformerAbstract;

class LassAirQualityResponseTransformer extends TransformerAbstract
{
    public function transform(LassDataset $dataset)
    {
        return $dataset->makeHidden([
            'site_id',
            'updated_at',
            'created_at',
        ])->toArray();
    }
}