<?php

namespace App\Console\Commands;

use App\Repository\RemoteEpaAirQualityRepository;
use App\Repository\RemoteEpaSitesRepository;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ImportAirQualityDataset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aqdc:import {source}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import air quality dataset';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $source = $this->argument('source');

        switch ($source) {
            case 'epa':
                // Get EAP data source configurations
                $epaBaseUrl = config('aqdc.remote_source.epa.base_url');
                $epaAirQualityUri = config('aqdc.remote_source.epa.air_quality_uri');
                $epaSitesUri = config('aqdc.remote_source.epa.site_uri');

                $client = new Client();

                $airQualityRepository = new RemoteEpaAirQualityRepository($epaBaseUrl, $client);
                $airQualityRepository->setUri($epaAirQualityUri);

                $siteRepository = new RemoteEpaSitesRepository($epaBaseUrl, $client);
                $siteRepository->setUri($epaSitesUri);

                break;
        }



        var_dump($siteRepository->getAll());
        var_dump($airQualityRepository->getAll());
    }
}
