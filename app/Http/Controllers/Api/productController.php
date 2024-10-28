<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;

class productController extends Controller
{

    private $messages = [
        'not_found' => ['message' => 'No se encontró el producto.', 'status' => 404],
        'found' => ['message' => 'Producto encontrado.', 'status' => 200],
    ];


   // ADMINISTRADOR
// Método para obtener todos los productos
public function index()
{
    // Obtener todos los productos
    $products = Products::all(); // Eliminamos la relación 'with('image')'
    // Verificar si no se encontraron productos
    if ($products->isEmpty()) {
        return response()->json(["message" => "No se encontró el producto"], 404);
    }
// Preparar y retornar respuesta JSON con los productos encontrados y mensaje de éxito
    return response()->json([
        'message' => 'Productos encontrados.',
        'products' => $products,
        'status' => 200,
    ], 200);
}


<<<<<<< HEAD
public function store(Request $request)
{
    // Validar los datos recibidos en la solicitud
    $validator = Validator::make($request->all(), [
        'NameProduct' => 'required',
        'Description' => 'required',
        'Price' => 'required|max:10',
        'Stock' => 'required',
        'NameCategory' => 'required|string|exists:categories,NameCategory',
        'NameSub' => 'required|string|exists:subcategories,NameSub',
        'ImageURL' => 'required|url',
        'Status' => 'required'
    ]);

    // Verificar si la validación falla
    if ($validator->fails()) {
        return response()->json(['message' => 'Error en la validación de los datos.', 'errors' => $validator->errors(), 'status' => 400], 400);
=======
        return response()->json($this->messages['found'], 200);
>>>>>>> Crud-Api
    }

    // Crear un nuevo producto con los datos validados
    $product = Products::create([
        'NameProduct' => $request->NameProduct,
        'Description' => $request->Description,
        'Price' => $request->Price,
        'Stock' => $request->Stock,
        'NameCategory' => $request->NameCategory,
        'NameSub' => $request->NameSub,
        'ImageURL' => $request->ImageURL,
        'Status' => $request->Status
    ]);

    // Retornar respuesta JSON con mensaje de éxito, datos del producto creado y código de estado 201
    return response()->json(['message' => 'Producto creado exitosamente.', 'product' => $product, 'status' => 201], 201);
}


         // Método para eliminar un producto
    public function destroy($id)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "description" => "required",
            "price" => "required| max:10",
            "stock" => "required",
            "category_id" => "required",
            "status" => "required",
        ]);

        if ($validator->fails()) {
            $data = [
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400,
            ];
            return response()->json($data, 400);
        }

        $products = Products::create([
            "name" => $request->name,
            "description" => $request->description,
            "price" => $request->price,
            "stock" => $request->stock,
            "category_id" => $request->category_id,
            "status" => $request->status,
        ]);

        //return response()->json(['products' => $products , 'status' => 201], 201);

        if (!$products) {
            $data = [
                "message" => "Error al crear el producto",
                "status" => 500,
            ];
            return response()->json($data, 500);
        }

        $data = [
            "products" => $products,
            "status" => 201,
        ];

        return response()->json($data, 201);

           /* if (auth()->user()->role !== 'admin')
        {
            return response()->json(['message' => 'No autorizado'], 403);
        }*/

        // Buscar el producto por ID
        $product = Products::find($id);

    // Verificar si el producto no se encuentra
    if (!$product) {
        return response()->json(['message' => 'No se encontró el producto', 'status' => 404], 404);
    }

    // Eliminar el producto encontrado
    $product->delete();

    // Retornar respuesta JSON con mensaje de éxito y código de estado 200
    return response()->json(['message' => 'Producto eliminado exitosamente.', 'status' => 200], 200);
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
        return response()->json(['message' => 'Producto no encontrado', 'status' => 404], 404);
    }

    // Validar los datos recibidos en la solicitud
    $validator = Validator::make($request->all(), [
        'NameProduct' => 'required|string|max:255',
        'Description' => 'required|string',
        'Price' => 'required|numeric',
        'Stock' => 'required|integer',
        'NameCategory' => 'required|string|exists:categories,NameCategory',
        'NameSub' => 'required|string|exists:subcategories,NameSub',
        'ImageURL' => 'required|url',
        'Status' => 'required|string',
    ]);

    // Verificar si la validación falla
    if ($validator->fails()) {
        return response()->json(['message' => 'Error en la validación de los datos.', 'errors' => $validator->errors(), 'status' => 400], 400);
    }

    // Actualizar el producto con los datos validados
    $product->update([
        'NameProduct' => $request->NameProduct,
        'Description' => $request->Description,
        'Price' => $request->Price,
        'Stock' => $request->Stock,
        'NameCategory' => $request->NameCategory,
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
        return response()->json(['message' => 'Producto no encontrado', 'status' => 404], 404);
    }

    // Preparar y retornar respuesta JSON con el producto encontrado y mensaje de éxito
    return response()->json([
        'message' => 'Producto encontrado.',
        'product' => $product,
        'status' => 200,
    ], 200);
}



public function indexcliente()
{
    // Obtener todos los productos
    $products = Products::all(); // Eliminamos la relación 'with('image')'

    // Verificar si no se encontraron productos
    if ($products->isEmpty()) {
        return response()->json(['message' => 'No se encontraron productos', 'status' => 404], 404);
    }

    // Preparar y retornar respuesta JSON con los productos encontrados y mensaje de éxito
    return response()->json([
        'message' => 'Productos encontrados.',
        'products' => $products,
        'status' => 200,
    ], 200);
}

}

