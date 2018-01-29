<?php

namespace App\Log;

use App\CollectionLog;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class CollectionLogHandler extends AbstractProcessingHandler
{
    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     * @return void
     */
    protected function write(array $record)
    {
        $collectionLog = new CollectionLog();
        $collectionLog->fill($record);
        $collectionLog->count = $record['context']['count'];
        $collectionLog->source_type = $record['context']['source_type'];
        $collectionLog->save();
    }
}