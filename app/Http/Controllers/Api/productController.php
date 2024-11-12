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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string', // Requiere valores no nulos y no vacíos
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'subcategory_id' => 'required|integer|exists:subcategories,id',
            'status' => 'required|string|in:activo,inactivo',
            'image_url' => 'nullable|url'
        ]);

        if ($validator->fails()) {
            return response()->json(array_merge($this->messages['validation_error'], ['errors' => $validator->errors()]), 400);
        }

        $product = Products::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'), // Asegúrate de que `description` se pase correctamente
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'subcategory_id' => $request->input('subcategory_id'),
            'status' => $request->input('status'),
            'image_url' => $request->input('image_url'),
        ]);

        if (!$product) {
            return response()->json($this->messages['creation_error'], 500);
        }

        return response()->json(array_merge($this->messages['created'], ['products' => $product]), 201);
    }

    // Método para eliminar un producto
    public function destroy($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json($this->messages['not_found'], 404);
        }

        $product->delete();
        return response()->json($this->messages['deleted'], 200);
    }

    public function update(Request $request, $id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json($this->messages['not_found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string', // Requiere valores no nulos y no vacíos
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'subcategory_id' => 'required|integer|exists:subcategories,id',
            'status' => 'required|string|in:activo,inactivo',
            'image_url' => 'nullable|url'
        ]);

        if ($validator->fails()) {
            return response()->json(array_merge($this->messages['validation_error'], ['errors' => $validator->errors()]), 400);
        }

        $product->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'), // Asegúrate de que `description` se pase correctamente
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'subcategory_id' => $request->input('subcategory_id'),
            'status' => $request->input('status'),
            'image_url' => $request->input('image_url'),
        ]);

        return response()->json(['message' => 'Producto actualizado exitosamente.', 'product' => $product, 'status' => 200], 200);
    }

    public function show($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json($this->messages['not_found'], 404);
        }

        return response()->json([
            'message' => $this->messages['found']['message'],
            'products' => $product,
            'status' => $this->messages['found']['status'],
        ], 200);
    }

    public function indexcliente()
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
}
