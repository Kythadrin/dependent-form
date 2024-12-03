<?php

declare(strict_types=1);

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CountryLanguageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/country', [CountryLanguageController::class, 'getCountries']);
Route::get('/language/{country}', [CountryLanguageController::class, 'getLanguages']);
Route::post('/register', [AuthController::class, 'registration']);
