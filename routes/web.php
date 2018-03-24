<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function() {
    return 'Hello transport api';
});

$router->post('/transits', ['as' => 'transits.store', 'uses' => 'TransitsController@store']);

$router->get('/reports/daily', ['as' => 'reports.daily', 'uses' => 'ReportsController@daily']);

$router->get('/reports/monthly', ['as' => 'reports.monthly', 'uses' => 'ReportsController@monthly']);


