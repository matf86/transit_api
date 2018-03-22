<?php

use App\Util\DistanceCalculator;
use App\Exceptions\DistanceCalculatorException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class DistanceCalculatorTest extends TestCase
{
    private function getDistanceCalculator($status, $body) {

        $mock = new MockHandler([
            new Response($status, [] ,$body),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        return new DistanceCalculator($client);
    }

    /** @test */

    function can_calculate_distance_between_two_addresses_when_gets_valid_response()
    {
        $address_A = 'ul. Dworcowa 1, Kórnik';
        $address_B = 'ul. Szwajcarska 1, Poznań';

        $valid_response = '{
           "destination_addresses" : [ "Szwajcarska 1, 61-285 Poznań, Poland" ],
           "origin_addresses" : [ "Władysława Zamoyskiego 7, 62-035 Kórnik, Poland" ],
           "rows" : [
              {
                 "elements" : [
                    {
                       "distance" : {
                          "text" : "20.1 km",
                          "value" : 20103
                       },
                       "duration" : {
                          "text" : "19 mins",
                          "value" : 1126
                       },
                       "status" : "OK"
                    }
                 ]
              }
           ],
           "status" : "OK"
        }';

        $distanceCalculator = $this->getDistanceCalculator(200, $valid_response);

        $result = $distanceCalculator->calculate($address_A, $address_B);

        $this->assertEquals(20103, $result);
    }

    /** @test */

    function returns_exception_when_one_of_addresses_can_not_be_found()
    {
        $this->expectException(DistanceCalculatorException::class);

        $elements_not_found_response_body = '{
           "destination_addresses" : [ "Szwajcarska 1, 61-285 Poznań, Poland" ],
           "origin_addresses" : [ "" ],
           "rows" : [
              {
                 "elements" : [
                    {
                       "status" : "NOT_FOUND"
                    }
            ]
            }
            ],
            "status" : "OK"
        }';

        $address_A = 'invalid-address-123';
        $address_B = 'ul. Szwajcarska 1, Poznań';

        $distanceCalculator = $this->getDistanceCalculator(200, $elements_not_found_response_body);

        $distanceCalculator->calculate($address_A, $address_B);
    }

    /** @test */

    function returns_exception_when_request_was_denied_by_api()
    {
        $this->expectException(DistanceCalculatorException::class);

        $request_denied_response_body = '{
            "destination_addresses": [],
            "error_message": "The provided API key is invalid.",
            "origin_addresses": [],
            "rows": [],
            "status": "REQUEST_DENIED"
        }';

        $address_A = 'ul. Dworcowa 1, Kórnik';
        $address_B = 'ul. Szwajcarska 1, Poznań';

        $distanceCalculator = $this->getDistanceCalculator(200, $request_denied_response_body);

        $distanceCalculator->calculate($address_A, $address_B);
    }
}