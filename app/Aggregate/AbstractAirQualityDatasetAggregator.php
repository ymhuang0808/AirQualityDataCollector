<?php

namespace App\Aggregate;


abstract class AbstractAirQualityDatasetAggregator implements AggregatorContract
{
    use HasAirQualityAggregatorSettings;

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

    public function __construct(AggregateProcessorContract $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @param AggregateProcessorContract $processor
     */
    public function setProcessor(AggregateProcessorContract $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @param string $source
     * @return $this
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
        return $this->getHourlyLastTimeBySource($this->source);
    }

    /**
     * @return string
     */
    protected function getDailyLastTime(): string
    {
        return $this->getDailyLastTimeBySource($this->source);
    }

    /**
     * @return string
     */
    protected function getWeeklyLastTime(): string
    {
        return $this->getWeeklyLastTimeBySource($this->source);
    }

    /**
     * @return string
     */
    protected function getMonthlyLastTime(): string
    {
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
}