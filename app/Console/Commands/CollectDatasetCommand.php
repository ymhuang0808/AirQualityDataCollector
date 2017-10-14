<?php

namespace App\Console\Commands;

use App\Factories\CollectAirboxDatasetCommandFactory;
use App\Factories\CollectEpaAirQualityCommandFactory;
use App\Factories\CollectEpaDatasetCommandFactory;
use App\Factories\CollectLassDatasetCommandFactory;
use Illuminate\Console\Command;

class CollectDatasetCommand extends Command
{
    protected $httpClient;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aqdc:collect-dataset {source*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect air quality dataset';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $multipleSource = $this->argument('source');

        $this->line(sprintf('It will collect air quality from %s', implode(', ', $multipleSource)));

        foreach ($multipleSource as $source) {

            $this->line(sprintf('Collect air quality from %s', $source));

            switch ($source) {
                case 'epa':
                    $factory = new CollectEpaDatasetCommandFactory();

                    break;

                case 'lass':
                    $factory = new CollectLassDatasetCommandFactory();

                    break;

                case 'airbox':
                    $factory = new CollectAirboxDatasetCommandFactory();

                    break;

                default:
                    $this->error('Unknown data source');

                    return;
            }

            $this->line(sprintf('Initialize to collect air quality'));

            $collectSitesCommand = $factory->createSitesCommand();
            $collectAirQualityCommand = $factory->createAirQualityCommand();
            $collectSitesCommand->execute();
            $collectAirQualityCommand->execute();

            $this->line(sprintf('Completed collecting air quality from %s', $source));
        }

        $this->line(sprintf('Completed collecting all source'));
    }
}
