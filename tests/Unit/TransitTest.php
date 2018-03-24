<?php

use Laravel\Lumen\Testing\DatabaseMigrations;


class TransitTest extends TestCase
{
    use DatabaseMigrations;

   /** @test */

   function can_get_formatted_date_attribute()
   {
       $transit = factory(\App\Transit::class)->create([
          'date' => \Carbon\Carbon::parse('2018-03-01')
       ]);

       $this->assertEquals('March, 1st', $transit->formatted_date);
   }
}