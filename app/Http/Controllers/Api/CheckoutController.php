<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; // Importa el modelo Product
use App\Models\Invoice; // Importa el modelo Invoice
use App\Models\DetailInvoice; // Importa el modelo DetailInvoice
use Illuminate\Support\Facades\DB; // Importa la clase DB para transacciones
use Illuminate\Support\Facades\Log; // Importa la clase Log para agregar logs de depuración

class CheckoutController extends Controller
{
    public function checkout(Request $request)
{
    // Valida los datos de entrada.
    $request->validate([
        'user_id' => 'required|integer|exists:users,id', // Valida que user_id sea un entero y exista en la tabla users
        'products' => 'required|array', // Valida que products sea un array
        'products.*.product_id' => 'required|integer|exists:products,id', // Valida que cada product_id sea un entero y exista en la tabla products
        'products.*.quantity' => 'required|integer|min:1', // Valida que cada quantity sea un entero y al menos 1
    ]);

    // Inicia una transacción de base de datos para asegurar la integridad de las operaciones.
    DB::beginTransaction();
    try {
        $total = 0; // Inicializa el total de la compra

        // Recorre cada producto en la solicitud para validar y calcular el total.
        foreach ($request->products as $item) {
            $product = Product::find($item['product_id']); // Busca el producto por ID
            Log::info('Producto: ' . $product->name . ', Stock: ' . $product->stock . ', Cantidad solicitada: ' . $item['quantity']);
            if (!$product) {
                return response()->json(['message' => 'Producto no encontrado', 'status' => 404], 404);
                // Retorna un error si el producto no es encontrado
            }
            if ($product->stock < $item['quantity']) {
                return response()->json(['message' => 'Stock insuficiente para el producto ' . $product->name], 400); // Retorna un error si el stock es insuficiente
            }
            $total += $product->price * $item['quantity'];
            // Calcula el total acumulando el precio de los productos
        }

        // Crea una nueva factura en la base de datos.
        $invoice = Invoice::create([
            'user_id' => $request->user_id, // Asigna el user_id de la solicitud a la factura
            'total' => $total, // Asigna el total calculado a la factura
        ]);

        // Recorre cada producto nuevamente para crear los detalles de la factura y actualizar el stock.
        foreach ($request->products as $item) {
            $product = Product::find($item['product_id']); // Busca el producto por ID
            $product->stock -= $item['quantity'];
            // Reduce el stock del producto basado en la cantidad comprada
            if ($product->stock == 0) {
                $product->status = 'inactivo';
                // Actualiza el estado del producto a 'inactivo' si el stock es 0
            }
            $product->save(); // Guarda los cambios en el producto

            // Crea un detalle de factura en la base de datos.
            DetailInvoice::create([
                'invoice_id' => $invoice->id, // Asigna el ID de la factura al detalle de factura
                'product_id' => $item['product_id'], // Asigna el ID del producto al detalle de factura
                'quantity' => $item['quantity'], // Asigna la cantidad comprada al detalle de factura
                'price' => $product->price, // Asigna el precio del producto al detalle de factura
            ]);
        }

        // Confirma la transacción si todo salió bien.
        DB::commit();
        return response()->json(['message' => 'Compra finalizada y factura generada.',
         'invoice' => $invoice, 'details' => $invoice->detailinvoices], 201);
    } catch (\Exception $e) {
        // Revertir la transacción en caso de error.
        DB::rollBack();
        return response()->json(['message' => 'Error al procesar la compra.', 
        'error' => $e->getMessage()], 500);
    }
}
    // Método para obtener una factura específica por ID
    public function getInvoiceById($id)
    {
        // Busca la factura con sus detalles por ID.
        $invoice = Invoice::with('detailinvoices')->where('id', $id)->first();
        if (!$invoice) {
            // Si la factura no se encuentra, retorna un error 404.
            return response()->json(['message' => 'Factura no encontrada', 'status' => 404], 404);
        }
        // Retorna la factura encontrada junto con sus detalles.
        return response()->json($invoice, 200);
    }
}
