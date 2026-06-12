<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CentralIntegrationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/central', [CentralIntegrationController::class, 'index']);
Route::post('/central/login', [CentralIntegrationController::class, 'login']);
Route::post('/central/audit', [CentralIntegrationController::class, 'audit']);
Route::post('/central/publish', [CentralIntegrationController::class, 'publish']);
