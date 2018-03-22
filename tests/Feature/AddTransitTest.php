<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AddTransitTest extends TestCase
{
    use DatabaseMigrations;

    private function valid_payload($overrides = []) {
        return array_merge([
            "source_address" => "ul. Zakręt 8, Poznań",
            "destination_address" =>"Złota 44, Warszawa",
            "price" => "450",
            "date" => "2018-03-15"
        ], $overrides);
    }

    /** @test */

    public function user_can_add_new_transit_data()
    {
        $payload = $this->valid_payload();

        $response = $this->post(route('transits.store'), $payload);

        $response->assertResponseStatus(200);

        $transit = \App\Transit::first();

        $this->assertEquals($payload['source_address'], $transit['source_address']);
        $this->assertEquals($payload['destination_address'], $transit['destination_address']);
        $this->assertEquals(45000, $transit['price']);
        $this->assertEquals($payload['date'], $transit['date']);
        $this->assertNotNull($transit['distance']);
    }

    /** @test */

    function source_address_is_required()
    {
        $payload = $this->valid_payload(['source_address' => '']);

        $response = $this->post(route('transits.store'), $payload);

        $transit = \App\Transit::first();

        $response->assertResponseStatus(422);
        $this->assertNull($transit);
    }

    /** @test */

    function source_address_must_be_a_string()
    {
        $payload = $this->valid_payload(['source_address' => 123545]);

        $response = $this->post(route('transits.store'), $payload);

        $transit = \App\Transit::first();

        $response->assertResponseStatus(422);
        $this->assertNull($transit);
    }

    /** @test */

    function destination_address_is_required()
    {
        $payload = $this->valid_payload(['destination_address' => '']);

        $response = $this->post(route('transits.store'), $payload);

        $transit = \App\Transit::first();

        $response->assertResponseStatus(422);
        $this->assertNull($transit);
    }

    /** @test */

    function destination_address_must_be_a_string()
    {
        $payload = $this->valid_payload(['destination_address' => 123545]);

        $response = $this->post(route('transits.store'), $payload);

        $transit = \App\Transit::first();

        $response->assertResponseStatus(422);
        $this->assertNull($transit);
    }

    /** @test */

    function price_is_required()
    {
        $payload = $this->valid_payload(['price' => '']);

        $response = $this->post(route('transits.store'), $payload);

        $transit = \App\Transit::first();

        $response->assertResponseStatus(422);
        $this->assertNull($transit);
    }

    /** @test */

    function price_must_be_numeric()
    {
        $payload = $this->valid_payload(['price' => '123wrong_type123']);

        $response = $this->post(route('transits.store'), $payload);

        $transit = \App\Transit::first();

        $response->assertResponseStatus(422);
        $this->assertNull($transit);
    }

    /** @test */

    function date_is_required()
    {
        $payload = $this->valid_payload(['date' => '']);

        $response = $this->post(route('transits.store'), $payload);

        $transit = \App\Transit::first();

        $response->assertResponseStatus(422);
        $this->assertNull($transit);
    }


    /** @test */

    function date_must_be_formatted()
    {
        $payload = $this->valid_payload(['date' => '01-09-2016']);

        $response = $this->post(route('transits.store'), $payload);

        $transit = \App\Transit::first();

        $response->assertResponseStatus(422);
        $this->assertNull($transit);
    }

}
