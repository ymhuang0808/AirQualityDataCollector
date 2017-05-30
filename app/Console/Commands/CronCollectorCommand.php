<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CronCollectorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:collector {source*} {--force|-F}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron run collector';

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
        //
    }
}
