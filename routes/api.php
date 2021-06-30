<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/animalData', 'animalDataController@getAnimalData');

Route::get('/animalData/{id}/{address}/detail', 'animalDataController@getAnimalDetailData');

Route::post('/register', 'Auth\RegisteredUserController@store');
// Route::post('/login', 'Auth\AuthenticatedSessionController@store');


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
