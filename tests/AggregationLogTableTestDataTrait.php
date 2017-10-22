<?php

namespace Tests;

trait AggregationLogTableTestDataTrait
{
    protected function generateDailyLassAggregationLogs()
    {
        return [
            [
                "aggregation_type"=>"daily",
                "source_type"=>"lass",
                "start_datetime"=>"2017-10-22 00:00:00",
                "end_datetime"=>"2017-10-22 23:59:59",
                "message"=>"Phasellus nulla. Integer",
                "level"=>"200"
            ],
            [
                "aggregation_type"=>"daily",
                "source_type"=>"lass",
                "start_datetime"=>"2017-10-23 00:00:00",
                "end_datetime"=>"2017-10-23 23:59:59",
                "message"=>"turpis egestas. Fusce aliquet magna a neque. Nullam ut",
                "level"=>"200"
            ],
            [
                "aggregation_type"=>"daily",
                "source_type"=>"lass",
                "start_datetime"=>"2017-10-24 00:00:00",
                "end_datetime"=>"2017-10-24 23:59:59",
                "message"=>"Etiam",
                "level"=>"200"
            ],
            [
                "aggregation_type"=>"daily",
                "source_type"=>"lass",
                "start_datetime"=>"2017-10-25 00:00:00",
                "end_datetime"=>"2017-10-25 23:59:59",
                "message"=>"gravida mauris ut mi. Duis",
                "level"=>"200"
            ],
            [
                "aggregation_type"=>"daily",
                "source_type"=>"lass",
                "start_datetime"=>"2017-10-26 00:00:00",
                "end_datetime"=>"2017-10-26 23:59:59",
                "message"=>"lobortis risus. In mi pede, nonummy ut, molestie in, tempus",
                "level"=>"200"
            ],
            [
                "aggregation_type"=>"daily",
                "source_type"=>"lass",
                "start_datetime"=>"2017-10-27 00:00:00",
                "end_datetime"=>"2017-10-27 23:59:59",
                "message"=>"leo, in lobortis",
                "level"=>"200"
            ],
            [
                "aggregation_type"=>"daily",
                "source_type"=>"lass",
                "start_datetime"=>"2017-10-28 00:00:00",
                "end_datetime"=>"2017-10-28 23:59:59",
                "message"=>"Donec elementum, lorem ut",
                "level"=>"200"
            ],
            [
                "aggregation_type"=>"daily",
                "source_type"=>"lass",
                "start_datetime"=>"2017-10-29 00:00:00",
                "end_datetime"=>"2017-10-29 23:59:59",
                "message"=>"sed dolor. Fusce mi lorem, vehicula et, rutrum",
                "level"=>"200"
            ],
        ];
    }
}
