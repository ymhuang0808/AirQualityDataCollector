<?php

namespace App\Aggregate;

use anlutro\LaravelSettings\Facade as Setting;
use Carbon\Carbon;

trait HasAirQualityAggregatorSettings
{
    private $datetime;

    private function getHourlyLastTimeBySource(string $source)
    {
        return Setting::get('aggregate.' . $source . '.hourly.air_quality', $this->getDefaultDateString());
    }

    private function getDailyLastTimeBySource(string $source)
    {
        return Setting::get('aggregate.' . $source . '.daily.air_quality', $this->getDefaultDateString());
    }

    private function getWeeklyLastTimeBySource(string $source)
    {
        return Setting::get('aggregate.' . $source . '.weekly.air_quality', $this->getDefaultDateString());
    }

    private function getMonthlyLastTimeBySource(string $source)
    {
        return Setting::get('aggregate.' . $source . '.monthly.air_quality', $this->getDefaultDateString());
    }

    private function setHourlyLastTimeBySource(string $source, string $datetime)
    {
        Setting::set('aggregate.' . $source . '.hourly.air_quality', $datetime);
        Setting::save();
    }

    private function setDailyLastTimeBySource(string $source, string $datetime)
    {
        Setting::set('aggregate.' . $source . '.daily.air_quality', $datetime);
        Setting::save();
    }

    private function setWeeklyLastTimeBySource(string $source, string $datetime)
    {
        Setting::set('aggregate.' . $source . '.monthly.air_quality', $datetime);
        Setting::save();
    }

    private function setMonthlyLastTimeBySource(string $source, string $datetime)
    {
        Setting::set('aggregate.' . $source . '.monthly.air_quality', $datetime);
        Setting::save();
    }

    private function getDefaultDateString(): string
    {
        if (!isset($this->datetime)) {
            $this->datetime = Carbon::create(2018, 01, 01);
        }

        return $this->datetime->toDateString();
    }
}