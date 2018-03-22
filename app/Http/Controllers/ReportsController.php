<?php

namespace App\Http\Controllers;

use App\Transit;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function daily(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d'
        ]);

        $result = Transit::dailyReport($request['start_date'], $request['end_date']);

        return response()->json($result, 200);
    }
}