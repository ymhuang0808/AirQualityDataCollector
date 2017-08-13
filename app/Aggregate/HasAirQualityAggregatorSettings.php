<?php

namespace App\Aggregate;

use anlutro\LaravelSettings\Facade as Setting;
use Carbon\Carbon;

trait HasAirQualityAggregatorSettings
{
    private $datetime;

    private function getHourlyLastTimeBySource(string $source)
    {
        return Setting::get('aggregate.' . $source . '.hourly.air_quality', Carbon::now());
    }

    private function getDailyLastTimeBySource(string $source)
    {
        return Setting::get('aggregate.' . $source . '.daily.air_quality', $this->getCurrentDateString());
    }

    private function getWeeklyLastTimeBySource(string $source)
    {
        return Setting::get('aggregate.' . $source . '.weekly.air_quality', $this->getCurrentDateString());
    }

    private function getMonthlyLastTimeBySource(string $source)
    {
        return Setting::get('aggregate.' . $source . '.monthly.air_quality', $this->getCurrentDateString());
    }

    private function setHourlyLastTimeBySource(string $source)
    {
        Setting::set('aggregate.' . $source . '.hourly.air_quality', Carbon::now());
    }

    private function setDailyLastTimeBySource(string $source)
    {
        Setting::set('aggregate.' . $source . '.monthly.air_quality', $this->getCurrentDateString());
    }

    private function setWeeklyLastTimeBySource(string $source)
    {
        Setting::set('aggregate.' . $source . '.monthly.air_quality', $this->getCurrentDateString());
    }

    private function setMonthlyLastTimeBySource(string $source)
    {
        Setting::set('aggregate.' . $source . '.monthly.air_quality', $this->getCurrentDateString());
    }

    private function getCurrentDateString(): string
    {
        if (!isset($this->datetime)) {
            $this->datetime = Carbon::create();
        }

        return $this->datetime->getCurrentDateString();
    }
}