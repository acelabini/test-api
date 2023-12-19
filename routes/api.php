<?php

use App\Http\Controllers\AmenityController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

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

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'projects'], function () {
        Route::get('search', [ProjectController::class, 'search']);
        Route::post('lead', [LeadController::class, 'store']);
    });
    Route::resource('projects', ProjectController::class)->parameters(['projects' => 'project:slug']);

    Route::group(['as' => 'management.', 'prefix' => 'management'], function () {
        Route::resource('amenities', AmenityController::class);

        Route::group(['as'=>'management.', 'prefix' => 'management'], function () {
            Route::resource('amenities', AmenityController::class);
            Route::group(['prefix' => 'articles'], function () {
                Route::resource('', ArticleController::class);
                Route::put('{article}/{status}', [ArticleController::class, 'patch']);
            });
        });

        Route::post('registration', [RegistrationController::class, 'register']);
    });
});
