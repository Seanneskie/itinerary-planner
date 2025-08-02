<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/reverse-geocode', function (Request $request) {
    $response = Http::get('https://geocoding-api.open-meteo.com/v1/reverse', [
        'latitude' => $request->query('latitude'),
        'longitude' => $request->query('longitude'),
        'count' => 1,
    ]);

    return $response->json();
});

