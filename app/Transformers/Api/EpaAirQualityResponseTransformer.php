<?php

namespace App\Transformers\Api;


use App\EpaDataset;
use App\Site;
use League\Fractal\TransformerAbstract;

class EpaAirQualityResponseTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'site',
    ];

    public function transform(EpaDataset $dataset)
    {
        $result = $dataset->makeHidden([
            'id',
            'site_id',
            'updated_at',
            'created_at',
        ])->toArray();

        return$result;
    }

    public function includeSite(EpaDataset $epa)
    {
        $site = $epa->site()->first();

        return $this->item($site, new SiteResponseTransformer());
    }
}