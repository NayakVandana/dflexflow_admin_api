<?php

use App\Http\Controllers\API\UtilityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::controller(UtilityController::class)->group(function () {   
    Route::post('check-mobile-isregistered', 'checkMobileExist');
});
