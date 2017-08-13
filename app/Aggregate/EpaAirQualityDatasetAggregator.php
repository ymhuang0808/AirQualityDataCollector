<?php

namespace App\Aggregate;


class EpaAirQualityDatasetAggregator implements AggregatorContract
{
    use HasAirQualityAggregatorSettings;

    protected $processor;

    public function process()
    {
        // Check it should aggregate by hourly, daily, weekly or monthly period

        // Invoke processor to aggregate the data
    }

    public function setProcessor(AggregateProcessorContract $processor)
    {
        $this->processor = $processor;
    }

    protected function getHourlyLastTime(): string
    {
        return $this->getHourlyLastTimeBySource('epa');
    }

    protected function getDailyLastTime(): string
    {
        return $this->getDailyLastTimeBySource('epa');
    }

    protected function getWeeklyLastTime(): string
    {
        return $this->getWeeklyLastTimeBySource('epa');
    }

    protected function getMonthlyLastTime(): string
    {
        return $this->getMonthlyLastTimeBySource('epa');
    }

    protected function setHourlyLastTime()
    {
        $this->setHourlyLastTimeBySource('epa');

        return $this;
    }

    protected function setDailyLastTime()
    {
        $this->setDailyLastTimeBySource('epa');

        return $this;
    }

    protected function setWeeklyLastTime()
    {
        $this->setWeeklyLastTimeBySource('epa');

        return $this;
    }

    protected function setMonthlyLastTime()
    {
        $this->setMonthlyLastTimeBySource('epa');

        return $this;
    }
}