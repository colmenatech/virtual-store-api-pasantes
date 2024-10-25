<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\SubcategoriesController;

// Rutas para Autenticación de Usuarios
Route::post('/register', [AuthController::class, 'register']); // Registro de usuarios
Route::post('/login', [AuthController::class, 'login']); // Inicio de sesión de usuarios
Route::get('/login', function () {
    return response()->json(['message' => 'Please login.'], 401);
})->name('login'); // Redirección a la ruta de login si no está autenticado

// Rutas protegidas por middleware de autenticación
Route::middleware([EnsureFrontendRequestsAreStateful::class, 'auth:sanctum'])->group(function () {
    Route::get('user-profile', [AuthController::class, 'userProfile']); // Muestra el perfil del usuario autenticado
    Route::post('logout', [AuthController::class, 'logout']); // Cierre de sesión del usuario
    Route::get('users', [AuthController::class, 'allUsers']); // Muestra una lista de todos los usuarios

    // Rutas CRUD para Administrador (productos)
    Route::post('/products', [ProductController::class, 'store']); // Crear un nuevo producto (solo administrador)
    Route::put('/products/{id}', [ProductController::class, 'update']); // Actualizar un producto existente (solo administrador)
    Route::delete('/products/{id}', [ProductController::class, 'destroy']); // Eliminar un producto existente (solo administrador)
    Route::get('/products', [ProductController::class, 'index']); // Listar todos los productos (administrador)
    Route::get('/products/{id}', [ProductController::class, 'show']); // Obtener los detalles de un producto específico (administrador)

    // Rutas CRUD para Administrador (categorías)
    Route::post('/categories', [CategoriesController::class, 'store']); // Crear una nueva categoría (solo administrador)
    Route::put('/categories/{id}', [CategoriesController::class, 'update']); // Actualizar una categoría existente (solo administrador)
    Route::delete('/categories/{id}', [CategoriesController::class, 'destroy']); // Eliminar una categoría existente (solo administrador)
    Route::get('/categories', [CategoriesController::class, 'index']); // Listar todas las categorías (administrador)

    // Rutas CRUD para Administrador (subcategorías)
    Route::post('/subcategories', [SubcategoriesController::class, 'store']); // Crear una nueva subcategoría (solo administrador)
    Route::put('/subcategories/{id}', [SubcategoriesController::class, 'update']); // Actualizar una subcategoría existente (solo administrador)
    Route::delete('/subcategories/{id}', [SubcategoriesController::class, 'destroy']); // Eliminar una subcategoría existente (solo administrador)
    Route::get('/subcategories', [SubcategoriesController::class, 'index']); // Listar todas las subcategorías (administrador)
    Route::get('/subcategories/{id}', [SubcategoriesController::class, 'show']); // Obtener los detalles de una subcategoría específica (administrador)

    // Facturas
    Route::get('/invoice', [InvoiceController::class, 'index']); // Listar todas las facturas
    Route::get('/invoice/{id}', [InvoiceController::class, 'show']); // Obtener los detalles de una factura específica
});

// Rutas para Roles y Permisos
Route::post('/roles', [RolePermissionController::class, 'createRole']); // Crear un nuevo rol
Route::post('/permissions', [RolePermissionController::class, 'createPermission']); // Crear un nuevo permiso
Route::post('/roles/assign-permissions', [RolePermissionController::class, 'assignPermissionToRole']); // Asignar permisos
Route::post('/assign-role', [RolePermissionController::class, 'assignRole'])->middleware('auth:sanctum', 'role:admin');
