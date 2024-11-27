<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\SubcategoryController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\AuthController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\Api\CardController;



// Rutas para Autenticación de Usuarios
Route::post('/register', [AuthController::class, 'register']); // Registro de usuarios
Route::post('/login', [AuthController::class, 'login']); // Inicio de sesión de usuarios
Route::get('/login', function () {
    return response()->json(['message' => 'Please login.'], 401);
})->name('login'); // Redirección a la ruta de login si no está autenticado

// Rutas protegidas por middleware de autenticación
Route::middleware([EnsureFrontendRequestsAreStateful::class, 'auth:sanctum'])->group(function () {

    // Rutas para Roles y Permisos
    Route::post('/roles', [RolePermissionController::class, 'createRole']); // Crear un nuevo rol
    Route::post('/permissions', [RolePermissionController::class, 'createPermission']); // Crear un nuevo permiso
    Route::post('/roles/assign-permissions', [RolePermissionController::class, 'assignPermissionToRole']); // Asignar permisos a un rol existente
    Route::post('/assign-role', [RolePermissionController::class, 'assignRole']); // Asignar un rol a un usuario
    Route::delete('/roles/{id}', [RolePermissionController::class, 'deleteRole']); // Eliminar un rol
    Route::delete('/permissions/{id}', [RolePermissionController::class, 'deletePermission']); // Eliminar un permiso
    Route::get('/roles', [RolePermissionController::class, 'getAllRoles']); // Listar todos los roles
    Route::get('/permissions', [RolePermissionController::class, 'getAllPermissions']); // Listar todos los permisos


    Route::get('user-profile', [AuthController::class, 'userProfile']); // Muestra el perfil del usuario autenticado
    Route::post('logout', [AuthController::class, 'logout']); // Cierre de sesión del usuario
    Route::get('users', [AuthController::class, 'allUsers']); // Muestra una lista de todos los usuarios


    //FACTURA
    // Rutas para compras
    Route::post('user-profile/checkout', [CheckoutController::class, 'checkout']);// Realizar una compra
    Route::get('user-profile/checkout/{id}', [CheckoutController::class, 'getInvoiceById']); // Obtener una factura específica
    Route::get('user-profile/invoice', [InvoiceController::class, 'index']); // Listar todas las facturas
    Route::get('user-profile/invoice/{id}', [InvoiceController::class, 'show']); // Obtener los detalles de una factura específica

    //TARJETA
    Route::get('user-profile/cards', [CardController::class, 'index']); // Listar todas las tarjetas del usuario
    Route::post('user-profile/cards', [CardController::class, 'store']);    // Crear una nueva tarjeta
    Route::get('user-profile/cards/{id}', [CardController::class, 'show']); // Obtener los detalles de una tarjeta específica
    Route::put('user-profile/cards/{id}', [CardController::class, 'update']); // Actualizar una tarjeta existente
    Route::delete('user-profile/cards/{id}', [CardController::class, 'destroy']); // Eliminar una tarjeta existente

    //CATEGORIAS
    Route::get('user-profile/categories', [CategoryController::class, 'index']); // Listar todas las categorías
    Route::get('user-profile/categories/{id}', [CategoryController::class, 'show']);//Buscar categoria en especifico
    // Middleware para rutas protegidas por roles de administrador

    //SUBCATEGORIAS
    Route::get('user-profile/subcategories', [SubcategoryController::class, 'index']); // Listar todas las subcategorías
    Route::get('user-profile/subcategories/{id}', [SubcategoryController::class, 'show']); // Obtener los detalles de una subcategoría específica


    Route::middleware(['role:admin'])->group(function () {
        // Rutas CRUD para Administradores (productos)
        Route::get('user-profile/products', [ProductController::class, 'index']); // Listar todos los productos
        Route::get('user-profile/products/{id}', [ProductController::class, 'show']); // Obtener los detalles de un producto específico
        Route::get('user-profile/products/subcategory/{subcategoryId}', [ProductController::class, 'getProductsBySubcategory']);  // Ruta para obtener productos por subcategoría
       
        Route::post('user-profile/products', [ProductController::class, 'store']); // Crear un nuevo producto
        Route::put('user-profile/products/{id}', [ProductController::class, 'update']); // Actualizar un producto existente
        Route::delete('user-profile/products/{id}', [ProductController::class, 'destroy']); // Eliminar un producto existente

        // Rutas CRUD para Administradores (categorías)
        Route::post('user-profile/categories', [CategoryController::class, 'store']); // Crear una nueva categoría
        Route::put('user-profile/categories/{id}', [CategoryController::class, 'update']); // Actualizar una categoría existente
        Route::delete('user-profile/categories/{id}', [CategoryController::class, 'destroy']); // Eliminar una categoría existente

        // Rutas CRUD para Administrador (subcategorías)
        Route::post('user-profile/subcategories', [SubcategoryController::class, 'store']); // Crear una nueva subcategoría
        Route::put('user-profile/subcategories/{id}', [SubcategoryController::class, 'update']); // Actualizar una subcategoría existente
        Route::delete('user-profile/subcategories/{id}', [SubcategoryController::class, 'destroy']); // Eliminar una subcategoría existente
    });

    Route::middleware(['role:client'])->group(function () {
        Route::get('user-profile/products', [ProductController::class, 'indexcliente']);
        Route::get('user-profile/products/{id}', [ProductController::class, 'showcliente']);
        Route::get('user-profile/products/subcategory/{subcategoryId}', [ProductController::class, 'getProductsBySubcategorycliente']);
    });
      
    
});




