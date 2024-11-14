<?php

use Illuminate\Support\Facades\Route;

// Ruta para la vista de bienvenida
Route::get("/", function () {
    return view("welcome");
})->name('home');

// Ruta para iniciar sesión
Route::get('/login', function () {
    return response()->json(['message' => 'Please login.'], 401);
})->name('login');

// Ruta para servir la aplicación React
//Route::get('/{any}', function () {
 // return view('react');
//})->where('any', '.*');
