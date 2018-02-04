<?php

namespace App\Aggregate;


use App\Aggregate\Contracts\AggregateProcessorContract;
use App\Aggregate\Contracts\AggregatorContract;

abstract class AbstractAirQualityDatasetAggregator implements AggregatorContract
{
    use HasAirQualityAggregatorSettings;

    protected $availableSource = [];

    /**
     * The source of dataset
     * @var string
     */
    protected $source;

    /**
     * Aggregate processor
     * @var AggregateProcessorContract
     */
    protected $processor;

    /**
     * AbstractAirQualityDatasetAggregator constructor.
     * @param AggregateProcessorContract|null $processor
     */
    public function __construct(AggregateProcessorContract $processor = null)
    {
        if (!is_null($processor)) {
            $this->processor = $processor;
        }

        // To get the available source, it retrieves the remote source type from config/aqdc.php
        $remoteSource = config('aqdc.remote_source');
        $this->availableSource = array_keys($remoteSource);
    }

    /**
     * Sets an aggregate processor
     *
     * @param AggregateProcessorContract $processor
     * @return AbstractAirQualityDatasetAggregator
     */
    public function setProcessor(AggregateProcessorContract $processor)
    {
        $this->processor = $processor;
        return $this;
    }

    /**
     * @param string $source
     * @return AbstractAirQualityDatasetAggregator
     */
    public function setSource(string $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return string
     */
    protected function getHourlyLastTime(): string
    {
        $this->forceReloadSettings();
        return $this->getHourlyLastTimeBySource($this->source);
    }

    /**
     * @return string
     */
    protected function getDailyLastTime(): string
    {
        $this->forceReloadSettings();
        return $this->getDailyLastTimeBySource($this->source);
    }

    /**
     * @return string
     */
    protected function getWeeklyLastTime(): string
    {
        $this->forceReloadSettings();
        return $this->getWeeklyLastTimeBySource($this->source);
    }

    /**
     * @return string
     */
    protected function getMonthlyLastTime(): string
    {
        $this->forceReloadSettings();
        return $this->getMonthlyLastTimeBySource($this->source);
    }

    /**
     * @param string $datetime
     * @return $this
     */
    protected function setHourlyLastTime(string $datetime)
    {
        $this->setHourlyLastTimeBySource($this->source, $datetime);

        return $this;
    }

    /**
     * @param string $datetime
     * @return $this
     */
    protected function setDailyLastTime(string $datetime)
    {
        $this->setDailyLastTimeBySource($this->source, $datetime);

        return $this;
    }

    /**
     * @param string $datetime
     * @return $this
     */
    protected function setWeeklyLastTime(string $datetime)
    {
        $this->setWeeklyLastTimeBySource($this->source, $datetime);

        return $this;
    }

    /**
     * @param string $datetime
     * @return $this
     */
    protected function setMonthlyLastTime(string $datetime)
    {
        $this->setMonthlyLastTimeBySource($this->source, $datetime);

        return $this;
    }

    public function getAvailableSource(): array
    {
        return $this->availableSource;
    }
}