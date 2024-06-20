<?php

use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\RegistrationController;
use App\Http\Controllers\API\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::controller(LoginController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('load-app-config', 'loadAppConfig');
});

Route::controller(RegistrationController::class)->group(function () {
    Route::post('register', 'registerUser');
    Route::post('check-registration-validation', 'checkRegistraionValidation');
});

Route::group(['middleware' => 'token'], function () {

    Route::controller(LoginController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::post('profile', 'profile');
    });

    Route::controller(DepartmentController::class)->group(function () {
        Route::post('save-department', 'saveDepartment');
        Route::post('get-departments', 'getDepartment');
        Route::post('delete-department', 'deleteDepartment');
    });
});