<?php

namespace App\Http\Controllers;

use App\Exceptions\ReportException;
use App\Services\Reports\DailyReport;
use App\Services\Reports\MonthlyReport;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function daily(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d'
        ]);

        $result = DailyReport::generate($request['start_date'], $request['end_date']);

        return response()->json($result, 200);
    }

    public function monthly()
    {
        try {
            $result = MonthlyReport::generate();
        } catch (ReportException $e) {
            return response()->json('No data for given month', 200);
        }

        return response()->json($result, 200);
    }
}
