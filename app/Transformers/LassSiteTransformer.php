<?php

namespace App\Transformers;


use App\Site;

class LassSiteTransformer extends AbstractAqdcTransformer
{

    public function transform(\stdClass $data): RemoteModel
    {
        $fields = [
            'name' => $data->device_id,
            'coordinates' => [
                'latitude' => $data->gps_lat,
                'longitude' => $data->gps_lon,
            ],
            'source_type' => Site::LASS_SOURCE_TYPE
        ];

        $remoteModel = new RemoteModel($fields);

        return $remoteModel;
    }
}