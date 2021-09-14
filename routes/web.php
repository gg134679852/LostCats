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

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/logout', 'userControllers@logout');
    Route::post('{id}/addFavorite', 'userControllers@addFavorite');
    Route::delete('{id}/removeFavorite', 'userControllers@removeFavorite');
    Route::get('/CurrentUser', 'userControllers@getCurrentUser');
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
