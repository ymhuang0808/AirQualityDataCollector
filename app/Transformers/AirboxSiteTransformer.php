<?php

namespace App\Transformers;


use App\Site;

class AirboxSiteTransformer extends AbstractAqdcTransformer
{

    public function transform(\stdClass $data): RemoteModel
    {
        $fields = [
            'name' => $data->device_id,
            'humanized_name' => $data->device,
            'coordinates' => [
                'latitude' => $data->gps_lat,
                'longitude' => $data->gps_lon,
            ],
            'source_type' => Site::AIRBOX_SOURCE_TYPE
        ];

        $remoteModel = new RemoteModel($fields);

        return $remoteModel;
    }
}