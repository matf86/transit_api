<?php

namespace App;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Transit extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

    protected $dateFormat = 'Y-m-d';

    public $timestamps = false;

    public static function dailyReport($start_date, $end_date)
    {
        return self::whereBetween('date', [$start_date, $end_date])
                ->select(DB::raw("SUM(distance) as total_distance, SUM(price) as total_price"))
                ->get()->map(function($item) {
                    return [
                        'total_distance' => $item->formatted_total_distance,
                        'total_price' => $item->formatted_total_price
                    ];
                });
    }

    public static function monthlyReport()
    {
        $date_range = [
            Carbon::now()->startOfMonth()->format('Y-m-d'),
            Carbon::yesterday()->format('Y-m-d'),
        ];

        return self::whereBetween('date', $date_range)
            ->select('date', DB::raw('SUM(distance) as total_distance, AVG(price) as avg_price, AVG(distance) as avg_distance'))
            ->groupBy('date')
            ->get()->map(function($item) {
                return [
                    'date' => $item->formatted_date,
                    'total_distance' => $item->formatted_total_distance,
                    'avg_distance' => $item->formatted_average_distance,
                    'avg_price' => $item->formatted_average_price
                ];
            });
    }

    protected function getFormattedDateAttribute()
    {
        return $this->date->format('F, jS');
    }

    protected function getFormattedTotalDistanceAttribute()
    {
        return number_format($this->total_distance / 1000) .'km';
    }

    protected function getFormattedAverageDistanceAttribute()
    {
        return number_format($this->avg_distance / 1000) .'km';
    }

    protected function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price / 100, 2) .'PLN';
    }

    protected function getFormattedAveragePriceAttribute()
    {
        return number_format($this->avg_price / 100, 2) .'PLN';
    }
}
