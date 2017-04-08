<?php

namespace App\Transformers;


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
            'source_type' => 'lass'
        ];

        $remoteModel = new RemoteModel($fields);

        return $remoteModel;
    }
}