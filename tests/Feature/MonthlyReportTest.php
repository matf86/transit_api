<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class MonthlyReportTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */

    public function can_generate_monthly_report()
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
            'date' => '2018-02-28',
            'price'=> 45671,
            'distance' => 245622
        ]);

        $transit_05_1 = factory(\App\Transit::class)->create([
            'date' => '2018-04-01',
            'price'=> 45671,
            'distance' => 245622
        ]);

        $transit_06_22 = factory(\App\Transit::class)->create([
            'date' => '2019-03-22',
            'price'=> 45671,
            'distance' => 245622
        ]);

        $transit_07_1 = factory(\App\Transit::class)->create([
            'date' => '2017-03-22',
            'price'=> 45671,
            'distance' => 245622
        ]);

        $response = $this->get(route('reports.monthly'));

        $response->assertResponseStatus(200);

        $report = $this->response->getOriginalContent();

        $this->assertCount(3, $report);

        $this->assertEquals('March, 1st', $report[0]['date']);
        $this->assertEquals('60km', $report[0]['total_distance']);
        $this->assertEquals('30km', $report[0]['avg_distance']);
        $this->assertEquals('375.00PLN', $report[0]['avg_price']);

        $this->assertEquals('March, 2nd', $report[1]['date']);
        $this->assertEquals('40km', $report[1]['total_distance']);
        $this->assertEquals('40km', $report[1]['avg_distance']);
        $this->assertEquals('550.00PLN', $report[1]['avg_price']);

        $this->assertEquals('March, 3rd', $report[2]['date']);
        $this->assertEquals('52km', $report[2]['total_distance']);
        $this->assertEquals('26km', $report[2]['avg_distance']);
        $this->assertEquals('255.00PLN', $report[2]['avg_price']);

    }

    /** @test */
    public function generate_properly_formatted_response_when_no_data_was_found()
    {
        $response = $this->get(route('reports.monthly'));

        $response->assertResponseStatus(200);

        $this->assertEquals('No data for given month', $this->response->getOriginalContent());
    }
}
