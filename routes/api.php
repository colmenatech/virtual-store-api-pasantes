<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\Api\productController;
use App\Http\Controllers\Api\categoriesController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\Api\invoiceController;
use App\Http\Controllers\Api\subcategoriesController;



//Administrador
// Grupo de rutas CRUD para Administrador
//Route::group(['prefix' => 'admin'], function ()
//{
  // Ruta para crear un nuevo producto (solo administrador)
  Route::post('/products', [productController::class, 'store']);

  // Ruta para actualizar un producto existente (solo administrador)
  Route::put('/products/{id}', [productController::class, 'update']);

  // Ruta para eliminar un producto existente (solo administrador)
  Route::delete('/products/{id}', [productController::class, 'destroy']);

  // Ruta para listar todos los productos (administrador)
  Route::get('/products', [productController::class, 'index']);

  // Ruta para obtener los detalles de un producto específico (administrador)
  Route::get('/products/{id}', [productController::class, 'show']);
//});

// Grupo de rutas CRUD para Cliente
//Route::group(['prefix' => 'client'], function ()
 //{
  // Ruta para listar todos los productos (cliente)
  Route::get('/products', [productController::class, 'index']);

  // Ruta para obtener los detalles de un producto específico (cliente)
  Route::get('/products/{id}', [productController::class, 'show']);
//});

// Rutas para Autenticación de Usuarios
// Registro de usuarios
Route::post('/register', [AuthController::class, 'register']);

// Inicio de sesión de usuarios
Route::post('/login', [AuthController::class, 'login']);

// Redirección a la ruta de login si no está autenticado
Route::get('/login', function () {
    return response()->json(['message' => 'Please login.'], 401);
})->name('login');

//ROLES Y PERMISOS

// Ruta para crear un nuevo rol
Route::post('/roles', [RolePermissionController::class, 'createRole']);

// Ruta para crear un nuevo permiso
Route::post('/permissions', [RolePermissionController::class, 'createPermission']);

// Ruta para asignar permisos a un rol existente
Route::post('/roles/assign-permissions', [RolePermissionController::class, 'assignPermissionToRole']);


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



// Grupo de rutas CRUD para Administrador
//Route::group(['prefix' => 'admin'], function () {
  // Ruta para crear una nueva categoría (solo administrador)
  Route::post('/categories', [categoriesController::class, 'store']);

  // Ruta para actualizar una categoría existente (solo administrador)
  Route::put('/categories/{id}', [categoriesController::class, 'update']);

  // Ruta para eliminar una categoría existente (solo administrador)
  Route::delete('/categories/{id}', [categoriesController::class, 'destroy']);

  // Ruta para listar todas las categorías (administrador)
  Route::get('/categories', [categoriesController::class, 'index']);
//});


//Factura
  // Ruta para listar todas las facturas
  Route::get('/invoice', [InvoiceController::class, 'index']);

  // Ruta para obtener los detalles de una facura en específico
  Route::get('/invoice/{id}', [InvoiceController::class, 'show']);


  //SUBCATEGORÍAS
  // Rutas CRUD para Subcategorías
//Route::group(['prefix' => 'admin'], function () {
  Route::post('/subcategories', [subcategoriesController::class, 'store']);
  Route::put('/subcategories/{id}', [subcategoriesController::class, 'update']);
  Route::delete('/subcategories/{id}', [subcategoriesController::class, 'destroy']);
  Route::get('/subcategories', [subcategoriesController::class, 'index']);
  Route::get('/subcategories/{id}', [subcategoriesController::class, 'show']);
//});