<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\SubcategoriesController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\AuthController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// Rutas para productos gestionadas por el administrador
Route::post('/products', [ProductController::class, 'store']); // Crear un nuevo producto
Route::put('/products/{id}', [ProductController::class, 'update']); // Actualizar un producto existente
Route::delete('/products/{id}', [ProductController::class, 'destroy']); // Eliminar un producto existente
Route::get('/products', [ProductController::class, 'index']); // Listar todos los productos
Route::get('/products/{id}', [ProductController::class, 'show']); // Obtener los detalles de un producto específico

// Rutas para productos accesibles por los clientes
Route::get('/products', [ProductController::class, 'index']); // Listar todos los productos
Route::get('/products/{id}', [ProductController::class, 'show']); // Obtener los detalles de un producto específico

// Rutas para roles y permisos
Route::post('/roles', [RolePermissionController::class, 'createRole']); // Crear un nuevo rol
Route::post('/permissions', [RolePermissionController::class, 'createPermission']); // Crear un nuevo permiso
Route::post('/roles/assign-permissions', [RolePermissionController::class, 'assignPermissionToRole']); // Asignar permisos a un rol existente
Route::post('/assign-role', [RolePermissionController::class, 'assignRole']); // Asignar un rol a un usuario
Route::delete('/roles/{id}', [RolePermissionController::class, 'deleteRole']); // Eliminar un rol
Route::delete('/permissions/{id}', [RolePermissionController::class, 'deletePermission']); // Eliminar un permiso
Route::get('/roles', [RolePermissionController::class, 'getAllRoles']); // Listar todos los roles
Route::get('/permissions', [RolePermissionController::class, 'getAllPermissions']); // Listar todos los permisos

// Rutas para autenticación
Route::post('/login', [AuthController::class, 'login']); // Inicio de sesión
Route::post('/register', [AuthController::class, 'register']); // Registro de usuarios

// Rutas para categorías gestionadas por el administrador
Route::post('/categories', [CategoriesController::class, 'store']); // Crear una nueva categoría
Route::put('/categories/{id}', [CategoriesController::class, 'update']); // Actualizar una categoría existente
Route::delete('/categories/{id}', [CategoriesController::class, 'destroy']); // Eliminar una categoría existente
Route::get('/categories', [CategoriesController::class, 'index']); // Listar todas las categorías

// Rutas para facturas
Route::get('/invoice', [InvoiceController::class, 'index']); // Listar todas las facturas
Route::get('/invoice/{id}', [InvoiceController::class, 'show']); // Obtener los detalles de una factura específica

// Rutas para subcategorías gestionadas por el administrador
Route::post('/subcategories', [SubcategoriesController::class, 'store']); // Crear una nueva subcategoría
Route::put('/subcategories/{id}', [SubcategoriesController::class, 'update']); // Actualizar una subcategoría existente
Route::delete('/subcategories/{id}', [SubcategoriesController::class, 'destroy']); // Eliminar una subcategoría existente
Route::get('/subcategories', [SubcategoriesController::class, 'index']); // Listar todas las subcategorías
Route::get('/subcategories/{id}', [SubcategoriesController::class, 'show']); // Obtener los detalles de una subcategoría específica

// Rutas para compras


// Rutas para compras
Route::post('/checkout', [CheckoutController::class, 'checkout']); // Realizar una compra
Route::get('/checkout/{id}', [CheckoutController::class, 'getInvoiceById']); // Obtener una factura específica
