<?php

namespace App\Transformers\GeoJSON;


use App\ModelMeasurementContract;

class GeneralPointTransformer extends AbstractPointTransformer
{
    public function transform(ModelMeasurementContract $measurement)
    {
        $site = $measurement->getSite();
        $longitude = $site->coordinates['longitude'];
        $latitude = $site->coordinates['latitude'];
        $coordinates = [$longitude, $latitude];

        $measurementProperties = $measurement->getMeasurementPayload();
        $measurementProperties['published_datetime'] = $measurement->getPublishedDateTime()->toIso8601String();
        $siteProperties = $site->makeHidden([
            'id',
            'coordinates',
            'created_at',
            'updated_at',
        ])->toArray();

        $properties = array_merge($measurementProperties, $siteProperties);

        return $this->transformPoints($coordinates, $properties);
    }
}