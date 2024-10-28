<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\productController;
use App\Http\Controllers\Api\categoriesController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\subcategoriesController;
use App\Http\Controllers\Api\imagesController;

<<<<<<< HEAD
Route::get("/products", [productController::class, "index"]);

Route::get("/products/{id}", [productController::class, "show"]);

Route::post("/products", [productController::class, "store"]); // Agregar producto al carrito

Route::delete("/products/{id}", [productController::class, "destroy"]); // Eliminar producto del carrito

Route::put("/products/{id}", [productController::class, "update"]);

//Route::post('/products', [productController::class, 'checkout']); // Finalizar compra y generar factura
=======

//Productos

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

//ROLES Y PERMISOS

// Ruta para crear un nuevo rol
Route::post('/roles', [RolePermissionController::class, 'createRole']);

// Ruta para crear un nuevo permiso
Route::post('/permissions', [RolePermissionController::class, 'createPermission']);

// Ruta para asignar permisos a un rol existente
Route::post('/roles/assign-permissions', [RolePermissionController::class, 'assignPermissionToRole']);


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


//IMAGENES
// Importar el controlador de imágenes


// Definir la ruta para obtener todas las imágenes
Route::get('/images', [imagesController::class, 'index']);

// Definir la ruta para obtener una imagen específica por ID
Route::get('/images/{id}', [imagesController::class, 'show']);

// Definir la ruta para subir una nueva imagen
Route::post('/images', [imagesController::class, 'store']);

// Definir la ruta para actualizar una imagen existente
Route::put('/images/{id}', [imagesController::class, 'update']);

// Definir la ruta para eliminar una imagen
Route::delete('/images/{id}', [imagesController::class, 'destroy']);


>>>>>>> Crud-Api
