<?php

use Laravel\Lumen\Testing\DatabaseMigrations;


class GenerateMonthlyReportTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function can_generate_monthly_report()
    {
        factory(\App\Transit::class)->create([
            'date' => \Illuminate\Support\Carbon::now()->startOfMonth()->format('Y-m-d'),
            'price'=> 50000,
            'distance' => 35000
        ]);
        factory(\App\Transit::class)->create([
            'date' => \Illuminate\Support\Carbon::now()->startOfMonth()->format('Y-m-d'),
            'price'=> 25000,
            'distance' => 25000
        ]);
        factory(\App\Transit::class)->create([
            'date' => \Illuminate\Support\Carbon::now()->startOfMonth()->addDay()->format('Y-m-d'),
            'price'=> 55000,
            'distance' => 40000
        ]);
        factory(\App\Transit::class)->create([
            'date' => \Illuminate\Support\Carbon::now()->startOfMonth()->addDays(2)->format('Y-m-d'),
            'price'=> 50000,
            'distance' => 50000
        ]);
        factory(\App\Transit::class)->create([
            'date' => \Illuminate\Support\Carbon::now()->startOfMonth()->addDays(2)->format('Y-m-d'),
            'price'=> 1000,
            'distance' => 2000
        ]);
        factory(\App\Transit::class)->create([
            'date' => \Illuminate\Support\Carbon::now()->startOfMonth()->addMonths(2)->format('Y-m-d'),
            'price'=> 45671,
            'distance' => 245622
        ]);
        factory(\App\Transit::class)->create([
            'date' => \Illuminate\Support\Carbon::now()->startOfMonth()->subDays(2)->format('Y-m-d'),
            'price'=> 45671,
            'distance' => 245622
        ]);

        $result = \App\Services\Reports\MonthlyReport::generate();

        $this->assertCount(3, $result);
    }

    /** @test */
    function exception_is_thrown_when_there_is_no_data_for_given_month()
    {
        $this->expectException(\App\Exceptions\ReportException::class);

        $result = \App\Services\Reports\MonthlyReport::generate();
    }
}