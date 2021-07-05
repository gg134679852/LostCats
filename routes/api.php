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
Route::get('/animalData/getFilter', 'animalDataController@getAnimalDataFilter');
Route::post('/animalData/postFilter', 'animalDataController@postAnimalDataFilter');

Route::get('/animalData/{id}/{address}/detail', 'animalDataController@getAnimalDetailData');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/CurrentUser', 'userControllers@getCurrentUser');
    Route::post('{id}/addFavorite', 'userControllers@addFavorite');
    Route::post('{id}/removeFavorite', 'userControllers@removeFavorite');
});


