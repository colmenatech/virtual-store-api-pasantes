<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ShoppingCartController;
use App\Http\Controllers\Api\UsuariosController;
use App\Http\Controllers\Api\AuthController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// Productos - Administrador
Route::get('/products', [ProductController::class, 'index']); // Muestra todos los productos
Route::post('/products', [ProductController::class, 'store']); // Agrega o crea producto
Route::delete('/products/{id}', [ProductController::class, 'destroy']); // Elimina producto
Route::put('/products/{id}', [ProductController::class, 'update']); // Actualiza el producto

// Productos - Cliente
Route::get('/products', [ProductController::class, 'index']); // Muestra todos los productos
Route::get('/products/{id}', [ProductController::class, 'show']); // Muestra un producto específico

// Usuarios
Route::get('/Usuarios', [UsuariosController::class, 'mostrar']); // Muestra todos los usuarios
Route::post('/Usuarios/{id}', [UsuariosController::class, 'buscar']); // Busca un usuario específico
Route::post('/Usuarios', [UsuariosController::class, 'registrar']); // Registra un nuevo usuario

// Autenticación de usuarios
Route::post('/register', [AuthController::class, 'register']); // Registro
Route::post('/login', [AuthController::class, 'login']); // Inicio de sesión
Route::get('/login', function () {
    return response()->json(['message' => 'Please login.'], 401);
})->name('login'); // Ruta de login para redirección

// Rutas protegidas por middleware de autenticación
Route::middleware([EnsureFrontendRequestsAreStateful::class, 'auth:sanctum'])->group(function () {
    Route::get('user-profile', [AuthController::class, 'userProfile']); // Perfil de usuario
    Route::post('logout', [AuthController::class, 'logout']); // Cierre de sesión
});

// Otros
Route::get('users', [AuthController::class, 'allUsers']); // Muestra todos los usuarios