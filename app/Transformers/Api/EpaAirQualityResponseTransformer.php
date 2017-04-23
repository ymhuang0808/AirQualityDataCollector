<?php

namespace App\Transformers\Api;


use App\EpaDataset;
use League\Fractal\TransformerAbstract;

class EpaAirQualityResponseTransformer extends TransformerAbstract
{
    public function transform(EpaDataset $dataset)
    {
        return $dataset->makeHidden([
            'site_id',
            'updated_at',
            'created_at',
        ])->toArray();
    }
}