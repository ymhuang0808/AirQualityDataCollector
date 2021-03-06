<?php

namespace App\Transformers\Api;


use App\LassDataset;
use League\Fractal\TransformerAbstract;

class LassAirQualityResponseTransformer extends TransformerAbstract
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