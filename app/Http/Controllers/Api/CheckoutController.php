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
    // Método para procesar la compra y generar una factura
    public function checkout(Request $request)
    {
        // Valida los datos de entrada. Esto asegura que los datos proporcionados en la solicitud son correctos y existen en la base de datos.
        $request->validate([
            'user_id' => 'required|integer|exists:users,id', // Valida que user_id sea un entero y exista en la tabla users
            'products' => 'required|array', // Valida que products sea un array
            'products.*.product_id' => 'required|integer|exists:products,id', // Valida que cada product_id sea un entero y exista en la tabla products
            'products.*.quantity' => 'required|integer|min:1', // Valida que cada quantity sea un entero y al menos 1
        ]);

        // Inicia una transacción de base de datos. Esto asegura que todas las operaciones de la base de datos se completen exitosamente antes de confirmar los cambios.
        DB::beginTransaction();
        try {
            $total = 0; // Inicializa el total de la compra

            // Recorre cada producto en la solicitud para validar y calcular el total.
            foreach ($request->products as $item) {
                $product = Product::find($item['product_id']); // Busca el producto por ID
                Log::info('Producto: ' . $product->name . ', Stock: ' . $product->stock . ', Cantidad solicitada: ' . $item['quantity']); // Log de información del producto
                if (!$product) {
                    // Si el producto no se encuentra, retorna un error 404.
                    return response()->json(['message' => 'Producto no encontrado', 'status' => 404], 404);
                }
                if ($product->stock < $item['quantity']) {
                    // Si el stock es insuficiente, retorna un error 400.
                    return response()->json(['message' => 'Stock insuficiente para el producto ' . $product->name], 400);
                }
                // Calcula el total de la compra sumando el precio del producto multiplicado por la cantidad.
                $total += $product->price * $item['quantity'];
            }

            // Crea una nueva factura en la base de datos.
            $invoice = Invoice::create([
                'user_id' => $request->user_id, // Asigna el user_id de la solicitud
                'total' => $total, // Asigna el total calculado
            ]);

            // Recorre cada producto nuevamente para crear los detalles de la factura y actualizar el stock.
            foreach ($request->products as $item) {
                $product = Product::find($item['product_id']); // Busca el producto por ID
                $product->stock -= $item['quantity']; // Descuenta la cantidad del stock
                $product->save(); // Guarda los cambios en el producto

                // Crea un detalle de factura en la base de datos.
                DetailInvoice::create([
                    'invoice_id' => $invoice->id, // Asigna el ID de la factura
                    'product_id' => $item['product_id'], // Asigna el ID del producto
                    'quantity' => $item['quantity'], // Asigna la cantidad
                    'price' => $product->price, // Asigna el precio del producto
                ]);
            }

            // Confirma la transacción si todo salió bien.
            DB::commit();
            // Retorna una respuesta de éxito con la factura y los detalles de la factura.
            return response()->json(['message' => 'Compra finalizada y factura generada.', 'invoice' => $invoice, 'details' => $invoice->detailinvoices], 201);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error para mantener la integridad de la base de datos.
            DB::rollBack();
            // Retorna una respuesta de error con el mensaje de excepción.
            return response()->json(['message' => 'Error al procesar la compra.', 'error' => $e->getMessage()], 500);
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
