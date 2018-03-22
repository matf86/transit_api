<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Transit extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public static function dailyReport($start_date, $end_date)
    {
        $result = self::whereBetween('date', [$start_date, $end_date])
                ->select(DB::raw("SUM(distance) as total_distance, SUM(price) as total_price"))
                ->get();

        return [
            'total_distance' => $result[0]->total_distance / 1000 ."km",
            'total_price' => $result[0]->total_price / 100 ."PLN",
        ];
    }

}
