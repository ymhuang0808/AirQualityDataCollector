<?php

namespace App\Transformers;


use App\Exceptions\TransformException;
use App\Site;

class AbstractLassCommunityAirQualityTransformer extends AbstractAqdcTransformer
{
    protected $sourceType = '';

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
            'site' => $this->getSiteByName($data->device_id, $this->sourceType),
        ];

        $remoteModel = new RemoteModel($fields, $relationships);

        return $remoteModel;
    }

    /**
     * @param string $string
     * @return string
     * @throws TransformException
     */
    protected function timestampToDateTime(string $string): string
    {
        $timezone = new \DateTimeZone('UTC');
        $dateTime = \DateTime::createFromFormat('Y-m-d\TH:i:sZ', $string, $timezone);

        if ($dateTime === false) {
            throw new TransformException('Incorrect DateTime format');
        }

        return $dateTime->format('Y-m-d H:i:s');
    }

    protected function getSiteByName(string $name, string $sourceType): Site
    {
        if (empty($sourceType)) {
            throw new \InvalidArgumentException('The source type is empty');
        }

        return Site::where('name', $name)
            ->where('source_type', $sourceType)
            ->first();
    }
}