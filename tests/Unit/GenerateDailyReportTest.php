<?php

use Laravel\Lumen\Testing\DatabaseMigrations;


class GenerateDailyReportTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function can_generate_report_for_given_date_range()
    {
        $transit_28_02 = factory(\App\Transit::class)->create([
            'date' => '2018-02-28',
            'price'=> 50000,
            'distance' => 5000
        ]);

        $transit_01_03 = factory(\App\Transit::class)->create([
            'date' => '2018-03-01',
            'price'=> 50000,
            'distance' => 35000
        ]);

        $transit_02_03 = factory(\App\Transit::class)->create([
            'date' => '2018-03-02',
            'price'=> 55000,
            'distance' => 40000
        ]);

        $transit_03_03 = factory(\App\Transit::class)->create([
            'date' => '2018-03-03',
            'price'=> 50000,
            'distance' => 50000
        ]);

        $transit_03_04 = factory(\App\Transit::class)->create([
            'date' => '2018-03-04',
            'price'=> 22222,
            'distance' => 33333
        ]);


        $start_date = '2018-03-01';
        $end_date = '2018-03-03';

        $result = \App\Services\Reports\DailyReport::generate($start_date, $end_date);

        $this->assertEquals('125km', $result[0]['total_distance']);
        $this->assertEquals('1,550.00PLN', $result[0]['total_price']);
    }
}