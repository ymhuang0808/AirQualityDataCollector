<?php

namespace App\Transformers\GeoJSON;


use League\Fractal\TransformerAbstract;

abstract class AbstractPointTransformer extends TransformerAbstract
{
    public function transformPoints($coordinates, $properties): array
    {
        $feature = [
            'type' => 'Feature',
            'geometry' => [
                'type' => 'Point',
                'coordinates' => $coordinates,
            ],
            'properties' => $properties,
        ];

        return $feature;
    }
}