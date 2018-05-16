<?php

namespace App\Transformers\Api;


use App\AggregationMetric;
use App\Site;
use Carbon\Carbon;
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
            ->transform(function (AggregationMetric $aggregationMetric) {
                $serialized = $aggregationMetric->makeHidden([
                    'id',
                    'site_id',
                    'period_type',
                    'created_at',
                    'updated_at',
                ])->toArray();

                $serialized['start_datetime'] = $aggregationMetric->start_datetime->toIso8601String();
                $serialized['end_datetime'] = $aggregationMetric->end_datetime->toIso8601String();

                return $serialized;
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