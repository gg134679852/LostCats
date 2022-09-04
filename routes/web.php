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
Route::post('/singup', 'userControllers@singup');
Route::post('/login', 'userControllers@login');
Route::get('/facebook/login','userControllers@faceBookLogin');
Route::get('/facebook/callback', 'userControllers@faceBookLoginCallBack');
Route::post('/spgateway/callback', 'donateController@callback');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', 'userControllers@logout');
    Route::post('{id}/addFavorite', 'userControllers@addFavorite');
    Route::delete('{id}/removeFavorite', 'userControllers@removeFavorite');
    Route::get('/CurrentUser', 'userControllers@getCurrentUser');
    Route::post('/spgateway/donate', 'donateController@Donate');
    Route::post('admin/animalData/createNewCatData', 'animalDataController@createNewCatData');
    Route::post('admin/animalData/uploadImage', 'animalDataController@uploadImage');
    Route::put('admin/animalData/updateCatData', 'animalDataController@updateCatData');
    Route::delete('admin/animalData/deleteCatData', 'animalDataController@deleteCatData');
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
