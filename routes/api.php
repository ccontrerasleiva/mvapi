<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MultivendeController;

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

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/{any}', [MultivendeController::class, 'getRequest'])->where('any', '.*');
    Route::post('/{any}',[MultivendeController::class, 'postRequest'])->where('any', '.*');
    Route::put('/{any}', [MultivendeController::class, 'putRequest'])->where('any', '.*');
});


