<?php

namespace App\Transformers\Api;


use App\AggregationMetric;
use App\Site;
use League\Fractal\TransformerAbstract;

class AggregationMeasurementsTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'site',
    ];

    public function transform(Site $site)
    {
        $aggregationMetrics = $site->aggregationMetric;
        $data = $aggregationMetrics->values()
            ->transform(function ($aggregationMetric) {
                return $aggregationMetric->makeHidden([
                    'id',
                    'site_id',
                    'period_type',
                    'created_at',
                    'updated_at',
                ]);
            })
            ->toArray();

        return [
            'aggregation_measurements' => $data,
        ];
    }

    public function includeSite(Site $site)
    {
        return $this->item($site, new SiteResponseTransformer);
    }
}