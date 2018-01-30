<?php

namespace App\Log;


use App\AggregationLog;
use Carbon\Carbon;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class AggregationLogHandler extends AbstractProcessingHandler
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
        /** @var \DateTimeImmutable $datetime */
        $datetime = $record['datetime'];
        $aggregationLog = new AggregationLog();
        $aggregationLog->fill($record['context']);
        $aggregationLog->message = $record['message'];
        $aggregationLog->created_at = Carbon::createFromTimestamp($datetime->getTimestamp());
        $aggregationLog->updated_at = Carbon::createFromTimestamp($datetime->getTimestamp());
        $aggregationLog->level = $record['level'];
        $aggregationLog->save();
    }
}