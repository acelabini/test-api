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
use \App\Http\Controllers\ImageController;

Route::get('/', function () {
    return '';
});

Route::group(['prefix' => 'cdn'], function () {
    Route::get('projects/{slug}/{type}/{subType}/{file}', [ImageController::class, 'projects']);
});

