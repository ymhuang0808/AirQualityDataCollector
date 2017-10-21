<?php

namespace App\Log;


use App\AggregationLog;
use Carbon\Carbon;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class AggregationLogHandler extends AbstractProcessingHandler
{
    /**
     * @var \App\AggregationLog
     */
    protected $aggregationLog;

    public function __construct(AggregationLog $aggregationLog, $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->aggregationLog = $aggregationLog;
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
        $this->aggregationLog->fill($record['context']);
        $this->aggregationLog->message = $record['message'];
        $this->aggregationLog->created_at = Carbon::createFromTimestamp($datetime->getTimestamp());
        $this->aggregationLog->updated_at = Carbon::createFromTimestamp($datetime->getTimestamp());
        $this->aggregationLog->level = $record['level'];
        $this->aggregationLog->save();
    }
}