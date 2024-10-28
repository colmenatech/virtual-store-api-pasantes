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
            return response()->json($this->messages['not_found'], 404);
        }

        return response()->json($this->messages['found'], 200);
    }



   // Método para crear un nuevo producto
   public function store(Request $request)
   {
     /*if ($request->user()->role !== 'admin')
        {
            return response()->json(['message' => 'No autorizado'], 403);
        }*/

       // Validar los datos recibidos en la solicitud
       $validator = Validator::make($request->all(), [
           'NameProduct' => 'required', // El nombre del producto es requerido
           'Description' => 'required', // La descripción del producto es requerida
           'Price' => 'required|max:10', // El precio es requerido y no debe superar los 10 caracteres
           'Stock' => 'required', // La cantidad de stock es requerida
           'NameCategory' => 'required|string|exists:categories,NameCategory',
           //El nombre de la categoria es relacionada con la tabla categories y es requerido
           'NameSub' => 'required|string|exists:subcategories,NameSub',
            // El NOMABRE de la subcategoría es relacionada con la tabla products y es requerido
           'ImageURL' => 'required|url', // La URL de la imagen es requerida y debe ser una URL válida
           'Status' => 'required' // El estado del producto es requerido
       ]);
            // Verificar si la validación falla
            if ($validator->fails()) {
                // Retornar respuesta JSON con mensaje de error en la validación y código de estado 400
                return response()->json([
                    'message' => 'Error en la validación de los datos.',
                    'errors' => $validator->errors(),
                    'status' => 400
                ], 400);
          }

        // Crear un nuevo producto con los datos validados
        $product = Products::create([
            'NameProduct' => $request->NameProduct,
            'Description' => $request->Description,
            'Price' => $request->Price,
            'Stock' => $request->Stock,
            'NameCategory' => $request->NameCategory,
            'NameSub' => $request->NameSub,
            'ImageURL' => $request->image_url, // Asignar la URL de la imagen
            'Status' => $request->Status
        ]);

        // Retornar respuesta JSON con mensaje de éxito, datos del producto creado y código de estado 201
        return response()->json([
            'message' => 'Producto creado exitosamente.',
            'product' => $product,
            'status' => 201,
        ], 201);
    }


         // Método para eliminar un producto
    public function destroy($id)
    {
           /* if (auth()->user()->role !== 'admin')
        {
            return response()->json(['message' => 'No autorizado'], 403);
        }*/

        // Buscar el producto por ID
        $product = Products::find($id);

        // Verificar si el producto no se encuentra
        if (!$product) {
            // Retornar respuesta JSON con mensaje de "No se encontró el producto" y código de estado 404
            return response()->json($this->messages['not_found'], 404);
        }

        // Eliminar el producto encontrado
        $product->delete();

        // Retornar respuesta JSON con mensaje de éxito y código de estado 200
        return response()->json([
            'message' => 'Producto eliminado exitosamente.', // Mensaje de éxito
            'status' => 200 // Código de estado 200
        ], 200);
    }




// Método para actualizar un producto
public function update(Request $request, $id)
{
      /*if ($request->user()->role !== 'admin')
    {            return response()->json(['message' => 'No autorizado'], 403);    }*/
    // Buscar el producto por ID
    $product = Products::find($id);

    // Verificar si el producto no se encuentra
    if (!$product) {
        // Retornar respuesta JSON con mensaje de "No se encontró el producto" y código de estado 404
        return response()->json($this->messages['not_found'], 404);
    }

    // Validar los datos recibidos en la solicitud
    $validator = Validator::make($request->all(), [
        'NameProduct' => 'required', // El nombre del producto es requerido
        'Description' => 'required', // La descripción del producto es requerida
        'Price' => 'required|max:10', // El precio es requerido y no debe superar los 10 caracteres
        'Stock' => 'required', // La cantidad de stock es requerida
        'NameCategory' => 'required|string|exists:categories,NameCategory',
        //El nombre de la categoría es requerido y se relaciona con la tabla de products
        'NameSub' => 'required|string|exists:subcategories,NameSub',
        // El nombre de la subcategoría es requerido y se relaciona con la tabla de products
        'ImageURL' => 'required|url', // Validar la URL de la imagen
        'Status' => 'required' // El estado del producto es requerido
    ]);

    // Verificar si la validación falla
    if ($validator->fails()) {
        // Retornar respuesta JSON con mensaje de error en la validación y código de estado 400
        return response()->json([
            'message' => 'Error en la validación de los datos.',
            'errors' => $validator->errors(),
            'status' => 400
        ], 400);
    }

    // Actualizar el producto con los datos validados
    $product->update([
        'NameProduct' => $request->NameProduct,
        'Description' => $request->Description,
        'Price' => $request->Price,
        'Stock' => $request->Stock,
        'NameCategory' => $request->NameCategory,
        'NameSub' => $request->NameSub,
        'ImageURL' => $request->ImageURL, // Actualizar la URL de la imagen
        'Status' => $request->Status
    ]);

    // Retornar respuesta JSON con mensaje de éxito, datos del producto actualizado y código de estado 200
    return response()->json([
        'message' => 'Producto actualizado exitosamente.',
        'product' => $product,
        'status' => 200
    ], 200);
}

    //CLIENTE
    // Método para obtener todos los productos para el cliente
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
            'message' => 'Productos encontrados.', // Mensaje de éxito
            'products' => $products, // Lista de productos encontrados
            'status' => 200, // Código de estado 200
        ], 200);
    }




    public function show($id)
    {
        // Buscar el producto por ID
        $product = Products::find($id); // Eliminamos la relación 'with('image')'

        // Verificar si el producto no se encuentra
        if (!$product) {
            return response()->json($this->messages['not_found'], 404);
        }

        // Preparar y retornar respuesta JSON con el producto encontrado y mensaje de éxito
        return response()->json([
            'message' => $this->messages['found']['message'], // Mensaje de éxito
            'product' => $product, // Producto encontrado
            'status' => $this->messages['found']['status'], // Código de estado 200
        ], 200);
    }



    // POST /checkout: Finalizar compra y generar factura
    public function checkout(Request $request)
    {
       // Implementar lógica para finalizar la compra y generar factura
        // Aquí se puede agregar la lógica para procesar el pago y generar la factura.
        return response()->json(['message' => 'Producto Finalizado en Orden', 'status' => 201], 201);
     }
}
