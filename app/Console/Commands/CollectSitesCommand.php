<?php

namespace App\Console\Commands;

use App\Factories\CollectEpaSitesCommandFactory;
use App\Factories\CollectLassSitesCommandFactory;
use Illuminate\Console\Command;

class CollectSitesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aqdc:collect-sites {source*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect air quality sites';

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

        $this->line(sprintf('It will collect air quality sites from %s', implode(', ', $multipleSource)));

        foreach ($multipleSource as $source) {

            $this->line(sprintf('Collect air quality sites from %s', $source));

            switch ($source) {
                case 'epa':
                    $factory = new CollectEpaSitesCommandFactory();

                    break;

                case 'lass':
                    $factory = new CollectLassSitesCommandFactory();

                    break;

                default:
                    $this->error('Unknown data source');

                    return;
            }

            $this->line(sprintf('Initialize to collect air quality sites'));

            $command = $factory->createCommand();
            $command->execute();

            $this->line(sprintf('Completed collecting air quality sites from %s', $source));
        }

        $this->line(sprintf('Completed collecting all source'));
    }
}
