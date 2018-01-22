<?php

namespace App\Transformers\Api;


use App\AirboxDataset;
use League\Fractal\TransformerAbstract;

class AirboxAirQualityResponseTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'site',
    ];

    public function transform(AirboxDataset $dataset)
    {
        return $dataset->makeHidden([
            'site_id',
            'updated_at',
            'created_at',
        ])->toArray();
    }

    public function includeSite(AirboxDataset $dataset)
    {
        $site = $dataset->site()->first();

        return $this->item($site, new SiteResponseTransformer());
    }
}