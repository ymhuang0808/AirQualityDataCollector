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
            'psi' => (int) $airQuality->PSI,
            'so2' => (double) $airQuality->SO2,
            'co' => (double) $airQuality->CO,
            'o3' => (int) $airQuality->O3,
            'pm10' => (int) $airQuality->PM10,
            'pm25' => (int) $airQuality->{'PM2.5'},
            'no2' => (int) $airQuality->NO2,
            'wind_speed' => (double) $airQuality->WindSpeed,
            'wind_direction' => (int) $airQuality->WindDirec,
            'fpmi' => (int) $airQuality->FPMI,
            'nox' => (double) $airQuality->NOx,
            'no' => (double) $airQuality->NO,
            'major_pollutant' => $airQuality->MajorPollutant,
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
