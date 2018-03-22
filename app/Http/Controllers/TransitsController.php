<?php

namespace App\Http\Controllers;

use App\Transit;
use App\Util\DistanceCalculator;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransitsController extends Controller
{
    public function store(Request $request, DistanceCalculator $calc)
    {
        $this->validate($request, [
            'source_address' => 'required|string',
            'destination_address' => 'required|string',
            'price' => 'required|numeric',
            'date' => 'required|date_format:Y-m-d'
        ]);

        try {
            $distance = $calc->calculate($request['source_address'], $request['destination_address']);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }

        $transit = Transit::create([
            'source_address' => $request['source_address'],
            'destination_address' => $request['destination_address'],
            'price' => (integer) $request['price'] * 100,
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'distance' => $distance
        ]);

        return response()->json($transit, 200);
    }
}
