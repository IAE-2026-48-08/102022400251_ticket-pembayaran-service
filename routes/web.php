<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/docs/api-docs.json', function () {
    return response()->file(storage_path('api-docs/api-docs.json'), [
        'Content-Type' => 'application/json'
    ]);
});

Route::get('/api-docs.json', function () {
    return response()->file(storage_path('api-docs/api-docs.json'), [
        'Content-Type' => 'application/json'
    ]);
});

Route::get('/swagger.json', function () {
    return response()->file(storage_path('api-docs/api-docs.json'), [
        'Content-Type' => 'application/json'
    ]);
});

Route::get('/docs', function () {
    return response()->file(storage_path('api-docs/api-docs.json'), [
        'Content-Type' => 'application/json'
    ]);
});

Route::get('/swagger-ui', function () {
    return redirect('/tickets/api-docs');
});

Route::get('/swagger-ui/index.html', function () {
    return redirect('/tickets/api-docs');
});
