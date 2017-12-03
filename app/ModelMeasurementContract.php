<?php

namespace App;


use Carbon\Carbon;

interface ModelMeasurementContract
{
    public function getMeasurementPayload(): array ;

    public function getPublishedDateTime(): Carbon;

    public function getSite(): Site;
}
