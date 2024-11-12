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

    Route::get('user-profile/products', [ProductController::class, 'index']); // Listar todos los productos
    Route::get('user-profile/products/{id}', [ProductController::class, 'show']); // Obtener los detalles de un producto específico


    // Middleware para rutas protegidas por roles de administrador
    Route::middleware(['role:admin'])->group(function () {
        // Rutas CRUD para Administradores (productos)
        Route::post('user-profile/products', [ProductController::class, 'store']); // Crear un nuevo producto
        Route::put('user-profile/products/{id}', [ProductController::class, 'update']); // Actualizar un producto existente
        Route::delete('user-profile/products/{id}', [ProductController::class, 'destroy']); // Eliminar un producto existente

        // Rutas CRUD para Administradores (categorías)
        Route::post('user-profile/categories', [CategoriesController::class, 'store']); // Crear una nueva categoría
        Route::put('user-profile/categories/{id}', [CategoriesController::class, 'update']); // Actualizar una categoría existente
        Route::delete('user-profile/categories/{id}', [CategoriesController::class, 'destroy']); // Eliminar una categoría existente
        Route::get('user-profile/categories', [CategoriesController::class, 'index']); // Listar todas las categorías

        // Rutas CRUD para Administrador (subcategorías)
        Route::post('user-profile/subcategories', [SubcategoriesController::class, 'store']); // Crear una nueva subcategoría
        Route::put('user-profile/subcategories/{id}', [SubcategoriesController::class, 'update']); // Actualizar una subcategoría existente
        Route::delete('user-profile/subcategories/{id}', [SubcategoriesController::class, 'destroy']); // Eliminar una subcategoría existente
        Route::get('user-profile/subcategories', [SubcategoriesController::class, 'index']); // Listar todas las subcategorías
        Route::get('user-profile/subcategories/{id}', [SubcategoriesController::class, 'show']); // Obtener los detalles de una subcategoría específica

        // Facturas
        Route::get('/invoice', [InvoiceController::class, 'index']); // Listar todas las facturas
        Route::get('/invoice/{id}', [InvoiceController::class, 'show']); // Obtener los detalles de una factura específica
    });

    // Middleware para rutas protegidas por roles de cliente
    Route::middleware(['role:client'])->group(function () {
       // Route::get('/products', [ProductController::class, 'index']); // Ver productos
       //Route::get('/products/{id}', [ProductController::class, 'show']); // Ver detalles de un producto específico
        //Route::post('/cart', [CartController::class, 'add']); // Agregar al carrito
       // Route::post('/purchase', [PurchaseController::class, 'makePurchase']); // Realizar compra
    });
});

// Rutas para Roles y Permisos
Route::post('/roles', [RolePermissionController::class, 'createRole']); // Crear un nuevo rol
Route::post('/permissions', [RolePermissionController::class, 'createPermission']); // Crear un nuevo permiso
Route::post('/roles/assign-permissions', [RolePermissionController::class, 'assignPermissionToRole']); // Asignar permisos a un rol existente
Route::post('/assign-role', [RolePermissionController::class, 'assignRole']); // Asignar un rol a un usuario
Route::delete('/roles/{id}', [RolePermissionController::class, 'deleteRole']); // Eliminar un rol
Route::delete('/permissions/{id}', [RolePermissionController::class, 'deletePermission']); // Eliminar un permiso
Route::get('/roles', [RolePermissionController::class, 'getAllRoles']); // Listar todos los roles
Route::get('/permissions', [RolePermissionController::class, 'getAllPermissions']); // Listar todos los permisos

// Rutas para compras


// Rutas para compras
Route::post('/checkout', [CheckoutController::class, 'checkout']); // Realizar una compra

Route::get('/checkout/{id}', [CheckoutController::class, 'getInvoiceById']); // Obtener una factura específica

