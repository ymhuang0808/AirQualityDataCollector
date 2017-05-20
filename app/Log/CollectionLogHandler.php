<?php

namespace App\Log;

use App\CollectionLog;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class CollectionLogHandler extends AbstractProcessingHandler
{
    protected $collectionLog;

    public function __construct(CollectionLog $collectionLog, $level = Logger::DEBUG, $bubble = true)
    {
        $this->collectionLog = $collectionLog;
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
        $this->collectionLog->fill($record);
        $this->collectionLog->count = $record['context']['count'];
        $this->collectionLog->source_type = $record['context']['source_type'];
        $this->collectionLog->save();
    }
}