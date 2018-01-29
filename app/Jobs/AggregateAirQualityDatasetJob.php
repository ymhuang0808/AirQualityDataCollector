<?php

namespace App\Jobs;

use App\Aggregate\Contracts\AggregateFactoryContract;
use App\Aggregate\Contracts\AggregatorContract;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class AggregateAirQualityDatasetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var string The aggregated source */
    protected $source;

    /**
     * Create a new job instance.
     *
     * @param string $source
     */
    public function __construct($source = 'all')
    {
        Log::debug('AggregateAirQualityDatasetJob was dispatched. The source is ' . $source);
        $this->source = $source;
    }

    /**
     * Execute the job.
     *
     * @param AggregatorContract $aggregator
     * @param AggregateFactoryContract $factory
     * @return void
     */
    public function handle(AggregatorContract $aggregator, AggregateFactoryContract $factory)
    {
        /** @var array $aggregatedSource It stores the handled source */
        $aggregatedSource = [];

        if ($this->source != 'all' && in_array($this->source, $aggregator->getAvailableSource())) {
            $aggregatedSource[] = $this->source;
        } elseif ($this->source == 'all') {
            $aggregatedSource = $aggregator->getAvailableSource();
        } else {
            Log::debug('AggregateAirQualityDatasetJob didn\'t handle the aggregation');
            return;
        }

        Log::debug('AggregateAirQualityDatasetJob aggregation source are ' . $aggregatedSource);

        foreach ($aggregatedSource as $source) {
            $processor = $factory->getAggregateProcessor($source);
            $aggregator->setSource($source)->setProcessor($processor)->process();
        }
    }
}
