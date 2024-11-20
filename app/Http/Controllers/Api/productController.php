<?php

namespace App\Http\Controllers\Api;

use App\Models\Product; // Asegúrate de usar el espacio de nombres correcto
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Asegúrate de importar Validator

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
        $products = Product::all(); // Cambiado de $Product a $products para consistencia
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
        $product = Product::create($request->all()); // Cambiado de $Product a $product

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
        // Buscar el producto por ID
        $product = Product::find($id);

        // Verificar si el producto no se encuentra
        if (!$product) {
            return response()->json($this->messages['not_found'], 404);
        }

        // Eliminar el producto encontrado
        $product->delete();

        return response()->json($this->messages['deleted'], 200);
    }

    // Método para actualizar un producto
    public function update(Request $request, $id)
    {
        // Buscar el producto por ID
        $product = Product::find($id);

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
            'subcategory_id' => 'required|integer|exists:subcategories,id',
            'status' => 'required|string',
            'image_url' => 'required|url'
        ]);

        // Verificar si la validación falla
        if ($validator->fails()) {
            return response()->json(array_merge($this->messages['validation_error'], ['errors' => $validator->errors()]), 400);
        }

        // Actualizar el producto con los datos validados
        $product->update($request->all());

        // Retornar respuesta JSON con mensaje de éxito, datos del producto actualizado y código de estado 200
        return response()->json(['message' => 'Producto actualizado exitosamente.', 'product' => $product, 'status' => 200], 200);
    }

    // Método para mostrar un producto específico
    public function show($id)
    {
        // Buscar el producto por ID
        $product = Product::find($id);

        // Verificar si el producto no se encuentra
        if (!$product) {
            return response()->json($this->messages['not_found'], 404);
        }

        // Preparar y retornar respuesta JSON con el producto encontrado y mensaje de éxito
        return response()->json([
            'message' => $this->messages['found']['message'],
            'product' => $product,
            'status' => $this->messages['found']['status'],
        ], 200);
    }

    // Método para obtener todos los productos para el cliente
    public function indexcliente()
    {
        // Obtener todos los productos
        $products = Product::all();

        // Verificar si no se encontraron productos
        if ($products->isEmpty()) {
            return response()->json($this->messages['not_found'], 404);
        }

        // Preparar y retornar respuesta JSON con los productos encontrados y mensaje de éxito
        return response()->json([
            'message' => $this->messages['found']['message'],
            'products' => $products,
            'status' => $this->messages['found']['status']
        ], 200);
    }


    // Función para buscar productos por ID de subcategoría
    public function getProductsBySubcategory($subcategory_id)
    {
        // Obtener los productos que pertenezcan a la subcategoría dada
        $products = Product::where('subcategory_id', $subcategory_id)->get();

        // Verificar si no se encontraron productos
        if ($products->isEmpty()) {
            return response()->json($this->messages['not_found'], 404);
        }

        // Retornar respuesta con los productos encontrados
        return response()->json([
            'message' => $this->messages['found']['message'],
            'products' => $products,
            'status' => $this->messages['found']['status']
        ], 200);
    }

}
