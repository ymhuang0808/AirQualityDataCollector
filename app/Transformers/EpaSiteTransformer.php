<?php

namespace App\Transformers;


use App\County;
use App\Site;
use App\Township;

class EpaSiteTransformer extends AbstractAqdcTransformer
{
    /**
     * @param \stdClass $site
     * @return RemoteModel
     */
    public function transform(\stdClass $site) : RemoteModel
    {
        $fields = [
            'name' => $site->SiteName,
            'humanized_name' => $site->SiteName,
            'humanized_eng_name' => $site->SiteEngName,
            'area_name' => $site->AreaName,
            'address' => $site->SiteAddress,
            'coordinates' => [
                'latitude' => $site->TWD97Lat,
                'longitude' => $site->TWD97Lon,
            ],
            'type' => $site->SiteType,
            'source_type' => Site::EPA_SOURCE_TYPE,
        ];
        $relationships = [
        'county' => $this->getCountyByName($site->County),
        'township' => $this->getTownshipByName($site->Township),
        ];

        $remoteModel = new RemoteModel($fields, $relationships);

        return $remoteModel;
    }

    /**
     * Get `Country` model by name
     *
     * @param  string $name
     * @return County
     */
    protected function getCountyByName(string $name) : County
    {
        return County::firstOrCreate(['name' => $name]);
    }

    /**
     * Get `Township` model by name
     *
     * @param string $name
     * @return Township
     */
    protected function getTownshipByName(string $name) : Township
    {
        return Township::firstOrCreate(['name' => $name]);
    }
}
