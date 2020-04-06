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
use App\Http\Middleware\DemoMiddleware;
// v1 version API
$api->version('v1', ['namespace' => 'App\Http\Controllers\Api\V1'], function ($api) {
    $api->group(['middleware' => ['api.locale']], function ($api) {
        $api->get('shift/all-shift',[
            'uses'=>'ShiftController@getAllShift'
        ]);
        $api->get('shift/get-shift',[
            'uses'=>'ShiftController@list'
        ]);
        $api->post('shift/create',[
            'uses'=>'ShiftController@create'
        ]);
        $api->post('shift/update',[
            'uses'=>'ShiftController@update'
        ]);
        $api->post('shift/delete',[
            'uses'=>'ShiftController@delete'
        ]);
    });
});
