<?php

namespace App\Transformers\Api;


use League\Fractal\TransformerAbstract;

class AirQualityMeasurementResponse extends TransformerAbstract
{
    public function transform(MultipleAirQualityContract $collection)
    {
        $result = [
            'measurements' => $collection->all(),
        ];

        return $result;
    }

}