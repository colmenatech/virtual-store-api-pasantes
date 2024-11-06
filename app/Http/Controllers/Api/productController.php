<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;

class productController extends Controller
{
    /*public function __construct()
    {
        $this->middleware('role:admin')->except(['index', 'show']);
        $this->middleware('role:client')->only(['index', 'show']);
    }*/

    private $messages = [
        'not_found' => ['message' => 'No se encontró el producto.', 'status' => 404],
        'found' => ['message' => 'Producto encontrado.', 'status' => 200],
        'validation_error' => ['message' => 'Error en la validación de los datos.', 'status' => 400],
        'created' => ['message' => 'Producto creado exitosamente.', 'status' => 201],
        'creation_error' => ['message' => 'Error al crear el producto.', 'status' => 500],
        'deleted' => ['message' => 'Producto eliminado exitosamente.', 'status' => 200], // Añadir mensaje de eliminado exitosamente

    ];


// ADMINISTRADOR
// Método para obtener todos los productos
public function index()
{
    // Obtener todos los productos
    $products = Products::all(); // Eliminamos la relación 'with('image')'
    // Verificar si no se encontraron productos
    if ($products->isEmpty()) {
        return response()->json($this->messages['not_found'], 404);
    }
    // Preparar y retornar respuesta JSON con los productos encontrados y mensaje de éxito
   return response()->json([
    'message' => $this->messages['found']['message'], // Mensaje de éxito
    'products' => $products, // Lista de categorías encontradas
    'status' => $this->messages['found']['status']], 200);
}

public function store(Request $request)
{
    // Validar los datos recibidos en la solicitud
    $validator = Validator::make($request->all(), [
        'NameProduct' => 'required',
        'Description' => 'required',
        'Price' => 'required|max:10',
        'Stock' => 'required',
        //'NameCategory' => 'required|string|exists:categories,NameCategory',
        'NameSub' => 'required|string|exists:subcategories,NameSub',
        'ImageURL' => 'required|url',
        'Status' => 'required'
    ]);


    // Verificar si la validación falla
    if ($validator->fails()) {
        return response()->json(['message' => 'Error en la validación de los datos.', 'errors' => $validator->errors(), 'status' => 400], 400);
        return response()->json($this->messages['found'], 200);

    }

   // Verificar si la validación falla
   if ($validator->fails()) {
    // Retornar respuesta de error en formato JSON con el mensaje de validación de datos
    return response()->json(array_merge($this->messages['validation_error'], ['errors' => $validator->errors()]), 400);
}


    // Crear un nuevo producto con los datos validados
    $product = Products::create([
        'NameProduct' => $request->NameProduct,
        'Description' => $request->Description,
        'Price' => $request->Price,
        'Stock' => $request->Stock,
        //'NameCategory' => $request->NameCategory,
        'NameSub' => $request->NameSub,
        'ImageURL' => $request->ImageURL,
        'Status' => $request->Status
    ]);

    // Verificar si la creación falla
    if (!$product) {
        // Retornar respuesta de error en formato JSON con mensaje de error en la creación
        return response()->json($this->messages['creation_error'], 500);
    }

    // Retornar respuesta exitosa en formato JSON con el mensaje de creación exitosa y datos de los productos
    return response()->json(array_merge($this->messages['created'], ['products' => $product]), 201);
}


     // Método para eliminar un producto
    public function destroy($id)
    {

        // Buscarel producto por ID
        $product = Products::find($id);

        // Verificar si el producto no se encuentra
        if (!$product) {
            // Retornar respuesta JSON con mensaje de "No se encontró el producto" y código de estado 404
            return response()->json($this->messages['not_found'], 404);
        }

         // Verificar si se encuentra el producto antes de eliminar
        if ($product) {
            // Eliminar el producto encontrado
            $product->delete();

            // Retornar respuesta JSON con mensaje de éxito y código de estado 200
            return response()->json($this->messages['deleted'], 200);
        }
    }




public function update(Request $request, $id)
{
    /*if ($request->user()->role !== 'admin') {
        return response()->json(['message' => 'No autorizado'], 403);
    }*/

    // Buscar el producto por ID
    $product = Products::find($id);

    // Verificar si el producto no se encuentra
    if (!$product) {
        return response()->json($this->messages['not_found'], 404);
    }

    // Validar los datos recibidos en la solicitud
    $validator = Validator::make($request->all(), [
        'NameProduct' => 'required|string|max:255',
        'Description' => 'required|string',
        'Price' => 'required|numeric',
        'Stock' => 'required|integer',
       // 'NameCategory' => 'required|string|exists:categories,NameCategory',
        'NameSub' => 'required|string|exists:subcategories,NameSub',
        'ImageURL' => 'required|url',
        'Status' => 'required|string',
    ]);

    // Verificar si la validación falla
    if ($validator->fails()) {
        return response()->json(array_merge($this->messages['validation_error'], ['errors' => $validator->errors()]), 400);
    }

    // Actualizar el producto con los datos validados
    $product->update([
        'NameProduct' => $request->NameProduct,
        'Description' => $request->Description,
        'Price' => $request->Price,
        'Stock' => $request->Stock,
       // 'NameCategory' => $request->NameCategory,
        'NameSub' => $request->NameSub,
        'ImageURL' => $request->ImageURL,
        'Status' => $request->Status,
    ]);

    // Retornar respuesta JSON con mensaje de éxito, datos del producto actualizado y código de estado 200
    return response()->json(['message' => 'Producto actualizado exitosamente.', 'product' => $product, 'status' => 200], 200);
}



public function show($id)
{
    // Buscar el producto por ID
    $product = Products::find($id);

    // Verificar si el producto no se encuentra
    if (!$product) {
        return response()->json($this->messages['not_found'], 404);
    }

    // Preparar y retornar respuesta JSON con el producto encontrado y mensaje de éxito
    return response()->json([
        'message' => $this->messages['found']['message'], // Mensaje de éxito
        'products' => $product, // Lista de categorías encontradas
        'status' => $this->messages['found']['status'], // Código de estado 200
    ], 200);
}



public function indexcliente()
{
    // Obtener todos los productos
    $products = Products::all(); // Eliminamos la relación 'with('image')'
    // Verificar si no se encontraron productos
    if ($products->isEmpty()) {
        return response()->json($this->messages['not_found'], 404);
    }
    // Preparar y retornar respuesta JSON con los productos encontrados y mensaje de éxito
   return response()->json([
    'message' => $this->messages['found']['message'], // Mensaje de éxito
    'products' => $products, // Lista de categorías encontradas
    'status' => $this->messages['found']['status']], 200);
}

}

