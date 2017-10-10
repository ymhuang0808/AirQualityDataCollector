<?php

namespace App\Console\Commands;

use App\Factories\CollectAirboxAirQualityCommandFactory;
use App\Factories\CollectEpaAirQualityCommandFactory;
use App\Factories\CollectLassAirQualityCommandFactory;
use Illuminate\Console\Command;

class CollectAirQualityDatasetCommand extends Command
{
    protected $httpClient;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aqdc:collect-air-quality {source*}';

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
                    $factory = new CollectEpaAirQualityCommandFactory();

                    break;

                case 'lass':
                    $factory = new CollectLassAirQualityCommandFactory();

                    break;

                case 'airbox':
                    $factory = new CollectAirboxAirQualityCommandFactory();

                    break;

                default:
                    $this->error('Unknown data source');

                    return;
            }

            $this->line(sprintf('Initialize to collect air quality'));

            $command = $factory->createCommand();
            $command->execute();

            $this->line(sprintf('Completed collecting air quality from %s', $source));
        }

        $this->line(sprintf('Completed collecting all source'));
    }
}
