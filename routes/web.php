<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::post('user/singup', 'userControllers@singup');
Route::post('user/login', 'userControllers@login');
Route::get('/facebook/login', 'userControllers@faceBookLogin');
Route::get('/facebook/callback', 'userControllers@faceBookLoginCallBack');
Route::post('/spgateway/callback', 'donateController@callback');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('user/logout', 'userControllers@logout');
    Route::get('user/CurrentUser', 'userControllers@getCurrentUser');
    Route::middleware('userChecker')->post('user/addFavorite/{id}', 'userControllers@addFavorite');
    Route::middleware('userChecker')->delete('user/removeFavorite/{id}', 'userControllers@removeFavorite');
    Route::middleware('userChecker')->get('user/donateLogData', 'userControllers@getUserDonateLogData');
    Route::middleware('userChecker')->post('/spgateway/donate', 'donateController@Donate');
    Route::middleware('adminChecker')->post('admin/animalData/createNewCatData', 'animalDataController@createNewCatData');
    Route::middleware('adminChecker')->post('admin/animalData/uploadImage', 'animalDataController@uploadImage');
    Route::middleware('adminChecker')->put('admin/animalData/updateCatData', 'animalDataController@updateCatData');
    Route::middleware('adminChecker')->delete('admin/animalData/deleteCatData', 'animalDataController@deleteCatData');
    Route::middleware('adminChecker')->post('admin/shelterData/createNewShelterData', 'animalDataController@createNewShelterData');
    Route::middleware('adminChecker')->put('admin/shelterData/updateShelterData', 'animalDataController@updateShelterData');
    Route::middleware('adminChecker')->delete('admin/shelterData/deleteShelterData', 'animalDataController@deleteShelterData');
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
