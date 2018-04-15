<?php

namespace App\Services\Reports;


use App\Transit;

class DailyReport
{
    public static function generate($start_date, $end_date)
    {
        return Transit::sumUpBetween($start_date, $end_date);
    }
}