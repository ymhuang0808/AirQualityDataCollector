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
    ];

    public function transform(Site $site)
    {
        $data = $site->makeHidden([
            'created_at',
            'updated_at',
        ])->toArray();

        unset($data['aggregation_metric']);

        return $data;
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
}