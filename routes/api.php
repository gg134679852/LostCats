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
Route::get('/animalData/getDetail', 'animalDataController@getAnimalDetailData');
Route::get('/animalData/getFilter', 'animalDataController@getAnimalDataFilter');
Route::post('user/singup', 'userControllers@singup');
Route::post('user/login', 'userControllers@login');


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('user/logout', 'userControllers@logout');
    Route::get('user/CurrentUser', 'userControllers@getCurrentUser');
    Route::middleware('userChecker')->post('user/addFavorite/{id}', 'userControllers@addFavorite');
    Route::middleware('userChecker')->delete('user/removeFavorite/{id}', 'userControllers@removeFavorite');
    Route::middleware('userChecker')->get('user/donateLogData', 'userControllers@getUserDonateLogData');
    Route::middleware('userChecker')->post('/spgateway/donate', 'donateController@Donate');
    Route::middleware('adminChecker')->post('admin/animalData/createNewCatData', 'adminController@createNewCatData');
    Route::middleware('adminChecker')->post('admin/animalData/uploadImage', 'adminController@uploadImage');
    Route::middleware('adminChecker')->put('admin/animalData/updateCatData', 'adminController@updateCatData');
    Route::middleware('adminChecker')->delete('admin/animalData/deleteCatData', 'adminController@deleteCatData');
    Route::middleware('adminChecker')->post('admin/shelterData/createNewShelterData', 'adminController@createNewShelterData');
    Route::middleware('adminChecker')->put('admin/shelterData/updateShelterData', 'adminController@updateShelterData');
    Route::middleware('adminChecker')->delete('admin/shelterData/deleteShelterData', 'adminController@deleteShelterData');
    Route::middleware('adminChecker')->get('admin/donateLogData', 'adminController@getAllDonateLogData');
});






