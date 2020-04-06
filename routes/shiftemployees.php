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
// $app->get('/', function () use ($app) {
//     return $app->version();
// });

$api = app('Dingo\Api\Routing\Router');

// v1 version API
$api->version('v1', ['namespace' => 'App\Http\Controllers\Api\V1'], function ($api) {
    $api->group(['middleware' => ['api.locale']], function ($api) {
        $api->get('shift/get-employees-shift',[
            'uses'=>'ShiftEmployeesController@getEmployeesShift'
        ]);
        $api->get('shift/filter-shift',[
            'uses'=>'ShiftEmployeesController@list'
        ]);
        $api->post('shift/register-shift',[
            'uses'=>'ShiftEmployeesController@registerShift'
        ]);
        $api->post('shift/check-in',[
           'uses'=>'ShiftEmployeesController@checkIn'
        ]);
        $api->post('shift/check-out',[
            'uses'=>'ShiftEmployeesController@checkOut'
        ]);
        $api->post('shift/confirm',[
            'uses'=>'ShiftEmployeesController@confirmShift'
        ]);
    });
});
