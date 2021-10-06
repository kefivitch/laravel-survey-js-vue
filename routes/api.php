<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(
    [
        'namespace'     =>  'App\Http\Controllers\API',
        'middleware'    =>  config('survey-manager.api_middleware'),
    ],
    function () {

        Route::resource('/survey', 'SurveyAPIController', ['only' => [
            'index', 'store', 'update', 'destroy', 'show',
        ]]);

        Route::resource('/survey/{survey}/result', 'SurveyResultAPIController');

        Orion::resource('users', UsersController::class);
    }
);
