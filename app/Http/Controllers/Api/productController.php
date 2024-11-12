<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;

class ProductController extends Controller
{
    private $messages = [
        'not_found' => ['message' => 'No se encontró el producto.', 'status' => 404],
        'found' => ['message' => 'Producto encontrado.', 'status' => 200],
        'validation_error' => ['message' => 'Error en la validación de los datos.', 'status' => 400],
        'created' => ['message' => 'Producto creado exitosamente.', 'status' => 201],
        'creation_error' => ['message' => 'Error al crear el producto.', 'status' => 500],
        'deleted' => ['message' => 'Producto eliminado exitosamente.', 'status' => 200],
    ];

    // Método para obtener todos los productos
    public function index()
    {
        $products = Products::all();
        if ($products->isEmpty()) {
            return response()->json($this->messages['not_found'], 404);
        }
        return response()->json([
            'message' => $this->messages['found']['message'],
            'products' => $products,
            'status' => $this->messages['found']['status']
        ], 200);
    }

    // Método para crear un nuevo producto
    public function store(Request $request)
    {
        // Validar los datos recibidos en la solicitud
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'subcategory_id' => 'required|integer|exists:subcategories,id',
            'status' => 'required|string|in:activo,inactivo',
            'image_url' => 'required|url'
        ]);

        // Verificar si la validación falla
        if ($validator->fails()) {
            return response()->json([
                'message' => $this->messages['validation_error']['message'],
                'errors' => $validator->errors(),
                'status' => $this->messages['validation_error']['status']
            ], 400);
        }

        // Crear un nuevo producto con los datos validados
        $product = Products::create($request->all());

        // Verificar si la creación falla
        if (!$product) {
            return response()->json($this->messages['creation_error'], 500);
        }

        // Retornar respuesta exitosa en formato JSON con el mensaje de creación exitosa y datos del producto
        return response()->json([
            'message' => $this->messages['created']['message'],
            'products' => $product,
            'status' => $this->messages['created']['status']
        ], 201);
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
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'subcategory_id' => 'required|integer|exists:subcategories,id', //subcategory_id
        'status' => 'required|string',
        'image_url' => 'required|url'
    ]);

    // Verificar si la validación falla
    if ($validator->fails()) {
        return response()->json(array_merge($this->messages['validation_error'], ['errors' => $validator->errors()]), 400);
    }

    // Actualizar el producto con los datos validados
    $product->update([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'stock' => $request->stock,
        'subcategory_id' => $request->subcategory_id,
        'status' => $request->status,
        'image_url' => $request->image_url
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

