<?php
/**
 * Created by PhpStorm.
 * User: aming
 * Date: 2017/3/12
 * Time: 下午4:20
 */

namespace App\Transformers;


use App\Site;

class EpaAirQualityTransformer extends AbstractAqdcTransformer
{
    public function transform(\stdClass $airQuality): RemoteModel
    {
        $fields = [
            'aqi' => (int) $airQuality->AQI,
            'so2' => (double) $airQuality->SO2,
            'co' => (double) $airQuality->CO,
            'co_8hr' => (double) $airQuality->CO_8hr,
            'o3' => (int) $airQuality->O3,
            'o3_8hr' => (int) $airQuality->O3_8hr,
            'pm10' => (int) $airQuality->PM10,
            'pm10_avg' => (int) $airQuality->PM10_AVG,
            'pm25' => (int) $airQuality->{'PM2.5'},
            'pm25_avg' => (int) $airQuality->{'PM2.5_AVG'},
            'no2' => (int) $airQuality->NO2,
            'wind_speed' => (double) $airQuality->WindSpeed,
            'wind_direction' => (int) $airQuality->WindDirec,
            'nox' => (double) $airQuality->NOx,
            'no' => (double) $airQuality->NO,
            'pollutant' => $airQuality->Pollutant,
            'status' => $airQuality->Status,
            'published_datetime' => $this->publishTimeToDateTime($airQuality->PublishTime),
        ];
        $relationships = [
            'site' => $this->getSiteByName($airQuality->SiteName),
        ];

        $remoteModel = new RemoteModel($fields, $relationships);

        return $remoteModel;
    }

    /**
     * Convert the publishTime field into DateTime format
     * @param string $string
     * @return string
     */
    protected function publishTimeToDateTime(string $string) : string
    {
        $timezone = new \DateTimeZone('Asia/Taipei');
        $dateTime = \DateTime::createFromFormat('Y-m-d G:i', $string, $timezone);

        return $dateTime->format('Y-m-d H:i:s');
    }

    /**
     * Get `Site` model by name
     *
     * @param $name
     * @return Site
     */
    protected function getSiteByName(string $name) : Site
    {
        return Site::where('name', $name)->first();
    }
}
