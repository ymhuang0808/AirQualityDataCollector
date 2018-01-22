<?php

namespace App\Transformers\GeoJSON;


use League\Fractal\Serializer\ArraySerializer;

class GeoJSONSerializer extends ArraySerializer
{

    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        $featureCollection = [
            'type' => 'FeatureCollection',
            'features' => $data,
        ];

        return [$resourceKey ?: 'geojson' => $featureCollection];
    }
}