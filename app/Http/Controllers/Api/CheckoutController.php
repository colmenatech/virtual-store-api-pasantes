<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products; // Asegúrate de que esto esté correcto
use App\Models\Invoice;
use App\Models\DetailInvoice;
use Illuminate\Support\Facades\DB; // Importar la fachada DB

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        // Validación de datos de entrada
        $request->validate([
            'user_id' => 'required|integer|exists:users,id', // Verifica que el user_id es un entero y existe en la tabla de usuarios
            'products' => 'required|array', // Verifica que la clave 'products' es un array
            'products.*.product_id' => 'required|integer|exists:products,id', // Verifica que cada producto tiene un product_id válido y existente
            'products.*.quantity' => 'required|integer|min:1', // Verifica que cada producto tiene una cantidad mínima de 1
        ]);

        // Iniciar una transacción de base de datos para asegurar que todas las operaciones se completen exitosamente
        DB::beginTransaction();
        try {
            // Inicializar el total de la compra
            $total = 0;

            // Recorrer los productos del carrito
            foreach ($request->products as $item) {
                // Buscar el producto por su ID
                $products = Products::find($item['product_id']); // Asegúrate de que esto esté correcto

                // Verificar si el producto existe
                if (!$products) {
                    return response()->json(['message' => 'Producto no encontrado', 'status' => 404], 404);
                }

                // Verificar si hay suficiente stock para el producto
                if ($products->Stock < $item['quantity']) {
                    // Si no hay suficiente stock, retornar una respuesta de error
                    return response()->json(['message' => 'Stock insuficiente para el producto ' . $products->NameProduct], 400);
                }

                // Calcular el total acumulando el precio del producto por la cantidad comprada
                $total += $products->Price * $item['quantity'];
            }

            // Crear factura y almacenarla en la base de datos
            $invoice = Invoice::create([
                'IdUser' => $request->user_id,
                'Total' => $total,
            ]);

            // Crear detalles de factura y actualizar stock
            foreach ($request->products as $item) {
                // Buscar el producto por su ID
                $products = Products::find($item['product_id']); // Asegúrate de que esto esté correcto
                $products->Stock -= $item['quantity']; // Descontar el stock del producto
                $products->save();

                // Crear detalles de factura
                DetailInvoice::create([
                    'IdInvoice' => $invoice->id,
                    'IdProduct' => $item['product_id'],
                    'Quantity' => $item['quantity'],
                    'Price' => $products->Price,
                ]);
            }

            // Confirmar transacción
            DB::commit();

            // Enviar la factura en formato JSON al frontend
            return response()->json(['message' => 'Compra finalizada y factura generada.', 'invoice' => $invoice, 'details' => $invoice->detailinvoices], 201);
        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();
            return response()->json(['message' => 'Error al procesar la compra.', 'error' => $e->getMessage()], 500);
        }
    }
}
