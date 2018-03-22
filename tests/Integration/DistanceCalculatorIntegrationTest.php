<?php

use App\Util\DistanceCalculator;
use GuzzleHttp\Client;

/**
 *@group integration
 */
class DistanceCalculatorIntegrationTest extends TestCase
{
    /** @test */
    
    function can_calculate_distance_between_two_addresses()
    {
        $address_A = 'ul. Zamoyskiego 7, Kórnik';
        $address_B = 'ul. Szwajcarska 1, Poznań';
        $pre_calculated_distance = 20103;

        $distanceCalculator = new DistanceCalculator(new Client());

        $result = $distanceCalculator->calculate($address_A, $address_B);

        $this->assertEquals($pre_calculated_distance, $result);
    }

    /** @test */

    function throw_an_exception_when_wrong_address_is_passed()
    {
        $this->expectException(\App\Exceptions\DistanceCalculatorException::class);

        $address_A = null;
        $address_B = 'ul. Szwajcarska 1, Poznań';

        $distanceCalculator = new DistanceCalculator(new Client());

        $distanceCalculator->calculate($address_A, $address_B);
    }
}