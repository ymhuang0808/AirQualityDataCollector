<?php

namespace App\Transformers\Api;


use App\County;
use App\Site;
use App\Township;
use League\Fractal\TransformerAbstract;

class SiteResponseTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'county',
        'township',
        'air_quality'
    ];

    public function transform(Site $site)
    {
        return $site->makeHidden([
            'created_at',
            'updated_at'
        ])->toArray();
    }

    public function includeCounty(Site $site)
    {
        $county = $site->county;

        return $this->item($county, function (County $county) {
            return $county->makeHidden([
                'created_at',
                'updated_at',
            ])->toArray();
        });
    }

    public function includeTownship(Site $site)
    {
        $township = $site->township;

        return $this->item($township, function (Township $township) {
            return $township->makeHidden([
                'created_at',
                'updated_at',
            ])->toArray();
        });
    }

    public function includeAirQuality(Site $site)
    {
        $sourceType = $site->source_type;

        // TODO: Handle the empty dataset

        switch ($sourceType) {
            case Site::EPA_SOURCE_TYPE:
                $transformer = new EpaAirQualityResponseTransformer();
                $airQuality = $site->epaDataset()->latest()->first();

                break;

            case Site::LASS_SOURCE_TYPE:
                $transformer = new LassAirQualityResponseTransformer();
                $airQuality = $site->lassDataset()->latest()->first();
        }

        return $this->item($airQuality, $transformer, 'air_quality');
    }
}