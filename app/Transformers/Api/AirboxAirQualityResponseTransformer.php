<?php

namespace App\Transformers\Api;


use League\Fractal\TransformerAbstract;

class AirboxAirQualityResponseTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'site',
    ];

    public function transform(LassDataset $dataset)
    {
        return $dataset->makeHidden([
            'site_id',
            'updated_at',
            'created_at',
        ])->toArray();
    }

    public function includeSite(LassDataset $epa)
    {
        $site = $epa->site()->first();

        return $this->item($site, new SiteResponseTransformer());
    }
}