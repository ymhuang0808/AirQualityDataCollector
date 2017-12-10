<?php

namespace App\Console\Commands;

use App\Managers\ArchivedMeasurementsManagerInterface;
use Illuminate\Console\Command;

class ArchiveMeasurementsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'measurements:archive {source}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archives a certain source type of measurements';

    /**
     * @var ArchivedMeasurementsManagerInterface
     */
    protected $archivedMeasurementsManager;

    /**
     * Create a new command instance.
     * @param ArchivedMeasurementsManagerInterface $archivedMeasurementsManager
     */
    public function __construct(ArchivedMeasurementsManagerInterface $archivedMeasurementsManager)
    {
        parent::__construct();

        $this->archivedMeasurementsManager = $archivedMeasurementsManager;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sourceType = $this->argument('source');
        $result = $this->archivedMeasurementsManager
            ->setSourceType($sourceType)
            ->process();

        if ($result) {
            $this->info('Measurements were archived successfully.');
        } else {
            $this->line('No measurements are archived.');
        }
    }
}
