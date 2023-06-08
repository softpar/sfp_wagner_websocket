<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;

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


Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/logout', 'logout');
    Route::post('/refresh', 'refresh');
    Route::post('/me', 'me');
});

Route::controller(MessageController::class)->group(function () {
    Route::get('/list-messages/{userTo}', 'listMessages');
    Route::get('/list-messages-paginate/{userTo}', 'listMessagesPaginate');
    Route::post('/messages/add', 'create');
});
