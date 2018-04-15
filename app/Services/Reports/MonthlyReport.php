<?php

namespace App\Services\Reports;

use App\Exceptions\ReportException;
use App\Transit;
use Carbon\Carbon;

class MonthlyReport
{
    public static function generate()
    {
        $start_of_month = Carbon::now()->startOfMonth()->format('Y-m-d');
        $last_day = Carbon::yesterday()->format('Y-m-d');

        $result = Transit::getDailyStatsFor($start_of_month, $last_day);

        if(count($result) == 0) {
            throw new ReportException();
        }

        return $result;
    }
}