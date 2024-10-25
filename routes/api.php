<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ShoppingCartController;
use App\Http\Controllers\Api\UsuariosController;
use App\Http\Controllers\Api\AuthController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// Rutas para Productos (Administrador)
// Muestra todos los productos
Route::get('/products', [ProductController::class, 'index']);

// Agrega o crea un producto nuevo
Route::post('/products', [ProductController::class, 'store']);

// Elimina un producto específico
Route::delete('/products/{id}', [ProductController::class, 'destroy']);

// Actualiza los detalles de un producto específico
Route::put('/products/{id}', [ProductController::class, 'update']);

// Rutas para Productos (Cliente)
// Muestra todos los productos disponibles
Route::get('/products', [ProductController::class, 'index']);

// Muestra los detalles de un producto específico
Route::get('/products/{id}', [ProductController::class, 'show']);

// Rutas para Usuarios
// Muestra una lista de todos los usuarios
Route::get('/Usuarios', [UsuariosController::class, 'mostrar']);

// Busca un usuario específico por su ID
Route::post('/Usuarios/{id}', [UsuariosController::class, 'buscar']);

// Registra un nuevo usuario
Route::post('/Usuarios', [UsuariosController::class, 'registrar']);

// Rutas para Autenticación de Usuarios
// Registro de usuarios
Route::post('/register', [AuthController::class, 'register']);

// Inicio de sesión de usuarios
Route::post('/login', [AuthController::class, 'login']);

// Redirección a la ruta de login si no está autenticado
Route::get('/login', function () {
    return response()->json(['message' => 'Please login.'], 401);
})->name('login');

// Rutas protegidas por middleware de autenticación
// Solo accesibles si el usuario está autenticado
Route::middleware([EnsureFrontendRequestsAreStateful::class, 'auth:sanctum'])->group(function () {
    // Muestra el perfil del usuario autenticado
    Route::get('user-profile', [AuthController::class, 'userProfile']);
    
    // Cierre de sesión del usuario
    Route::post('logout', [AuthController::class, 'logout']);
});

// Otras Rutas
// Muestra una lista de todos los usuarios
Route::get('users', [AuthController::class, 'allUsers']);
