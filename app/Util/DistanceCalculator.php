<?php

namespace App\Util;

use App\Exceptions\DistanceCalculatorException;
use GuzzleHttp\Client;


class DistanceCalculator
{
    const API_URI = 'https://maps.googleapis.com/maps/api/distancematrix/json';
    public $guzzle;

    public function __construct(Client $client)
    {
        $this->guzzle = $client;
    }

    public function calculate($starting_address, $destination)
    {
        $options = array(
            "units" => "metric",
            "origins" => $starting_address,
            "destinations" => $destination,
            "key" => env('GOOGLE_MATRIX_API_KEY')
        );

        $request = self::API_URI . "?" . http_build_query( $options );

        $result = $this->guzzle->get($request);

        $response = json_decode($result->getBody(), true);

        if($response['status'] !== 'OK') {
            throw new DistanceCalculatorException('Distance calculator exception. Top-level Status Code: ' . $response['status']);
        }

        if($response['rows'][0]['elements'][0]['status'] !== 'OK') {
            throw new DistanceCalculatorException('Distance calculator exception. Elements-level Status Code: ' . $response['rows'][0]['elements'][0]['status']);
        }

        return $response['rows'][0]['elements'][0]['distance']['value'];
    }

}