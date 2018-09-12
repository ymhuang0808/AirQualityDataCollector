<?php

namespace App\Transformers\Api;


use App\County;
use App\Site;
use App\Township;
use League\Fractal\TransformerAbstract;

class SiteResponseTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'latest_measurement',
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

    public function includeLatestMeasurement(Site $site)
    {
        $source = $site->source_type;
        $relation = $source . 'Dataset';
        $measurement = $site->{$relation}()->latestBySite()->get()->first();

        // Get the transformer class by source type
        $remoteSource = config('aqdc.remote_source');
        $transformer = $remoteSource[$source]['api_transformer'];

        // Omit the default included resource
        $this->getCurrentScope()->getManager()->parseExcludes('latest_measurement.site');

        return $this->item($measurement, new $transformer);
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