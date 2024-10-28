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
<<<<<<< HEAD
            return response()->json(
                ["message" => "No se encontró el producto"],
                404
            );
        }

        // Preparar la respuesta
        $data = [
            "products" => $products,
            "status" => 200,
        ];

        return response()->json($data, 200);
=======
            return response()->json($this->messages['not_found'], 404);
        }

        // Preparar y retornar respuesta JSON con los productos encontrados y mensaje de éxito
        return response()->json([
            'message' => 'Productos encontrados.', // Mensaje de éxito
            'products' => $products, // Lista de productos encontrados
            'status' => 200, // Código de estado 200
        ], 200);
>>>>>>> Crud-Api
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
<<<<<<< HEAD
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
=======
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
>>>>>>> Crud-Api
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
<<<<<<< HEAD
            $data = [
                "message" => "Producto no encontrado",
                "status" => 404,
            ];
            return response()->json($data, 404);
        }

        $data = [
            "products" => $product,
            "status" => 200,
        ];

        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $product = Products::find($id);

        if (!$product) {
            $data = [
                "message" => "Producto NO encontrado",
                "status" => 404,
            ];
            return response()->json($data, 404);
        }

        $product->delete();

        $data = [
            "message" => "Producto eliminado",
            "status" => 200,
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $product = Products::find($id);

        if (!$product) {
            $data = [
                "message" => "Producto NO encontrado",
                "status" => 404,
            ];
            return response()->json($data, 404);
        }

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

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category_id = $request->category_id;
        $product->status = $request->status;

        $product->save();

        $data = [
            "message" => "Producto actualizado",
            "products" => $product,
            "status" => 200,
        ];

        return response()->json($data, 200);
    }

    // public function updatePartial(Request $request, $id)
    // {
    //     $cart = shoppingcart::find($id);

    //     if (!$cart) {
    //         $data = [
    //             'message' => 'Carrito no encontrado',
    //             'status' => 404
    //         ];
    //         return response()->json($data, 404);
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'id' => $request->id,
    //         'user_id' => $request->user_id,
    //         'product_id' => $request->product_id,
    //         'quantity' => $request->quantity
    //     ]);

    //     if ($validator->fails()) {
    //         $data = [
    //             'message' => 'Error en la validación de los datos',
    //             'errors' => $validator->errors(),
    //             'status' => 400
    //         ];
    //         return response()->json($data, 400);
    //     }

    //     if ($request->has('id')) {
    //         $cart->id = $request->id;
    //     }

    //     if ($request->has('user_id')) {
    //         $cart->user_id = $request->user_id;
    //     }

    //     if ($request->has('product_id')) {
    //         $cart->product_id = $request->product_id;
    //     }

    //     if ($request->has('quantity')) {
    //         $cart->quantity= $request->quantity;
    //     }

    //     $cart->save();

    //     $data = [
    //         'message' => 'carrito actualizado',
    //         'shoppingcart' => $cart,
    //         'status' => 200
    //     ];

    //     return response()->json($data, 200);
    // }

=======
            return response()->json($this->messages['not_found'], 404);
        }

        // Preparar y retornar respuesta JSON con el producto encontrado y mensaje de éxito
        return response()->json([
            'message' => $this->messages['found']['message'], // Mensaje de éxito
            'product' => $product, // Producto encontrado
            'status' => $this->messages['found']['status'], // Código de estado 200
        ], 200);
    }



>>>>>>> Crud-Api
    // POST /checkout: Finalizar compra y generar factura
    public function checkout(Request $request)
    {
       // Implementar lógica para finalizar la compra y generar factura
        // Aquí se puede agregar la lógica para procesar el pago y generar la factura.
<<<<<<< HEAD

        return response()->json(
            [
                "message" => "Compra finalizada y factura generada",
                "status" => 201,
            ],
            201
        );
    }
=======
        return response()->json(['message' => 'Producto Finalizado en Orden', 'status' => 201], 201);
     }
>>>>>>> Crud-Api
}
