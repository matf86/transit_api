<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class DailyReportTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */

    public function can_generate_daily_report_for_given_date_range()
    {
        $transit_01_1 = factory(\App\Transit::class)->create([
            'date' => '2018-03-01',
            'price'=> 50000,
            'distance' => 35000
        ]);
        $transit_01_2 = factory(\App\Transit::class)->create([
            'date' => '2018-03-01',
            'price'=> 25000,
            'distance' => 25000
        ]);

        $transit_02_1 = factory(\App\Transit::class)->create([
            'date' => '2018-03-02',
            'price'=> 55000,
            'distance' => 40000
        ]);

        $transit_03_1 = factory(\App\Transit::class)->create([
            'date' => '2018-03-03',
            'price'=> 50000,
            'distance' => 50000
        ]);

        $transit_03_2 = factory(\App\Transit::class)->create([
            'date' => '2018-03-03',
            'price'=> 1000,
            'distance' => 2000
        ]);

        $transit_04_1 = factory(\App\Transit::class)->create([
            'date' => '2018-03-04',
            'price'=> 22222,
            'distance' => 33333
        ]);

        $transit_05_1 = factory(\App\Transit::class)->create([
            'date' => '2018-02-28',
            'price'=> 45671,
            'distance' => 245622
        ]);

        $start_date = '2018-03-01';
        $end_date = '2018-03-03';

        $response = $this->get(route('reports.daily', [
            'start_date'=>$start_date, 'end_date'=>$end_date
            ]
        ));

        $response->assertResponseStatus(200);

        $this->assertEquals('152km', $this->response->getData()[0]->total_distance);
        $this->assertEquals('1,810.00PLN', $this->response->getData()[0]->total_price);
    }

    /** @test */
    public function generate_properly_formatted_response_when_no_data_was_found()
    {
        factory(\App\Transit::class)->create([
            'date' => '2018-03-01',
            'price'=> 50000,
            'distance' => 35000
        ]);
        factory(\App\Transit::class)->create([
            'date' => '2018-03-01',
            'price'=> 25000,
            'distance' => 25000
        ]);

        $start_date = '2018-03-03';
        $end_date = '2018-03-05';

        $response = $this->get(route('reports.daily', [
                'start_date'=>$start_date, 'end_date'=>$end_date
            ]
        ));

        $response->assertResponseStatus(200);

        $this->assertEquals('0km', $this->response->getData()[0]->total_distance);
        $this->assertEquals('0.00PLN', $this->response->getData()[0]->total_price);
    }

    /** @test */

    function start_date_query_param_is_required()
    {
        $start_date = '';
        $end_date = '2018-03-03';

        $response = $this->get(route('reports.daily', [
                'start_date'=>$start_date, 'end_date'=>$end_date
            ]
        ));

        $response->assertResponseStatus(422);
    }


    /** @test */

    function start_date_query_param_must_be_formatted()
    {
        $start_date = '01-03-2018';
        $end_date = '2018-03-03';

        $response = $this->get(route('reports.daily', [
                'start_date'=>$start_date, 'end_date'=>$end_date
            ]
        ));

        $response->assertResponseStatus(422);
    }

    /** @test */

    function end_date_query_param_is_required()
    {
        $start_date = '2018-03-01';
        $end_date = null;

        $response = $this->get(route('reports.daily', [
                'start_date'=>$start_date, 'end_date'=>$end_date
            ]
        ));

        $response->assertResponseStatus(422);
    }


    /** @test */

    function end_date_query_param_must_be_formatted()
    {
        $start_date = '2018-03-01';
        $end_date = 'March 3 2018';

        $response = $this->get(route('reports.daily', [
                'start_date'=>$start_date, 'end_date'=>$end_date
            ]
        ));

        $response->assertResponseStatus(422);
    }
}
