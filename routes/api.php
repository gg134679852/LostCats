<?php

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
Route::get('/animalData/getSelect', 'animalDataController@getAnimalDataSelect');
Route::get('/animalData/getFilter/{address}/{sex}/{colour}', 'animalDataController@getAnimalDataFilter');

Route::get('/animalData/{id}/{address}/detail', 'animalDataController@getAnimalDetailData');
