<?php

namespace App\Console\Commands;

use App\Aggregate\Contracts\AggregateFactoryContract;
use App\Aggregate\Contracts\AggregatorContract;
use Illuminate\Console\Command;

class AggregateMeasurementsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'measurements:aggregate {source}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregate a certain type of measurements';

    /**
     * Aggregator
     *
     * @var AggregatorContract
     */
    protected $aggregator;

    /**
     * Aggregate factory to get a certain source type of processor
     *
     * @var AggregateFactoryContract
     */
    protected $aggregateFactory;

    /**
     * Create a new command instance.
     *
     * @param AggregatorContract $aggregator
     * @param AggregateFactoryContract $aggregateFactory
     */
    public function __construct(AggregatorContract $aggregator, AggregateFactoryContract $aggregateFactory)
    {
        parent::__construct();

        $this->aggregator = $aggregator;
        $this->aggregateFactory = $aggregateFactory;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $source = $this->argument('source');

        /** @var array $aggregatedSource It stores the handled source */
        $aggregatedSource = [];

        if ($source != 'all' && in_array($source, $this->aggregator->getAvailableSource())) {
            $aggregatedSource[] = $source;
        } elseif ($source == 'all') {
            $aggregatedSource = $this->aggregator->getAvailableSource();
        } else {
            $message = sprintf('%s source is not available.', $source);
            $this->error($message);
            return;
        }

        foreach ($aggregatedSource as $source) {
            $message = sprintf('%s source is prepared to aggregate', $source);
            $this->line($message);

            $processor = $this->aggregateFactory->getAggregateProcessor($source);
            $this->aggregator->setSource($source)->setProcessor($processor)->process();
        }

        $this->line('All sources were aggregated successfully.');
    }
}
