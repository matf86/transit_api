<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class MonthlyReportTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_generate_monthly_report()
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

        $response = $this->get(route('reports.monthly'));

        $response->assertResponseStatus(200);

        $report = $this->response->getOriginalContent();

        $this->assertCount(3, $report);

        $this->assertEquals('60km', $report[0]['total_distance']);
        $this->assertEquals('30km', $report[0]['avg_distance']);
        $this->assertEquals('375.00PLN', $report[0]['avg_price']);

        $this->assertEquals('40km', $report[1]['total_distance']);
        $this->assertEquals('40km', $report[1]['avg_distance']);
        $this->assertEquals('550.00PLN', $report[1]['avg_price']);

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
