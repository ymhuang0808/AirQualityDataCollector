<?php
/**
 * Created by PhpStorm.
 * User: aming
 * Date: 2017/4/8
 * Time: 下午3:18
 */

namespace App\Transformers;


use App\Site;

class LassAirQualityTransformer extends AbstractAqdcTransformer
{

    public function transform(\stdClass $data): RemoteModel
    {
        $fields = [
            'pm25' => property_exists($data, 's_d0') ? $data->s_d0 : null,
            'pm10' => property_exists($data, 's_d1') ? $data->s_d1 : null,
            'temperature' => property_exists($data, 's_t0') ? $data->s_t0 : null,
            'humidity' => property_exists($data, 's_h0') ? $data->s_h0 : null,
            'published_datetime' => $this->timestampToDateTime($data->timestamp),
        ];
        $relationships = [
            'site' => $this->getSiteByName($data->device_id),
        ];

        $remoteModel = new RemoteModel($fields, $relationships);

        return $remoteModel;
    }

    protected function timestampToDateTime(string $string): string
    {
        $timezone = new \DateTimeZone('UTC');
        $dateTime = \DateTime::createFromFormat('Y-m-d\TH:i:sZ', $string, $timezone);

        return $dateTime->format('Y-m-d H:i:s');
    }

    protected function getSiteByName(string $name): Site
    {
        return Site::where('name', $name)
            ->where('source_type', 'lass')
            ->first();
    }
}