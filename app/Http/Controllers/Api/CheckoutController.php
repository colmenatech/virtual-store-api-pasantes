<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Invoice;
use App\Models\DetailInvoice;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Método para realizar una compra y generar una factura
    public function checkout(Request $request)
    {
        // Validación de datos de entrada
        $request->validate([
            'user_id' => 'required|integer|exists:users,id', // Verifica que el user_id es un entero y existe en la tabla de usuarios
            'products' => 'required|array', // Verifica que la clave 'products' es un array
            'products.*.product_id' => 'required|integer|exists:products,id', // Verifica que cada producto tiene un product_id válido y existente
            'products.*.quantity' => 'required|integer|min:1', // Verifica que cada producto tiene una cantidad mínima de 1
        ]);

        // Iniciar una transacción de base de datos
        DB::beginTransaction();
        try {
            $total = 0; // Inicializar el total de la compra
            foreach ($request->products as $item) {
                $product = Products::find($item['product_id']); // Buscar el producto por su ID
                if (!$product) {
                    return response()->json(['message' => 'Producto no encontrado', 'status' => 404], 404);
                }
                if ($product->Stock < $item['quantity']) {
                    return response()->json(['message' => 'Stock insuficiente para el producto ' . $product->NameProduct], 400);
                }
                $total += $product->Price * $item['quantity']; // Calcular el total acumulando el precio del producto por la cantidad comprada
            }

            // Crear factura y almacenarla en la base de datos
            $invoice = Invoice::create([
                'IdUser' => $request->user_id,
                'Total' => $total,
            ]);

            // Crear detalles de factura y actualizar stock
            foreach ($request->products as $item) {
                $product = Products::find($item['product_id']);
                $product->Stock -= $item['quantity']; // Descontar el stock del producto
                $product->save();

                DetailInvoice::create([
                    'IdInvoice' => $invoice->IdInvoice,
                    'IdProduct' => $item['product_id'],
                    'Quantity' => $item['quantity'],
                    'Price' => $product->Price,
                ]);
            }

            // Confirmar transacción
            DB::commit();
            return response()->json(['message' => 'Compra finalizada y factura generada.', 'invoice' => $invoice, 'details' => $invoice->detailinvoices], 201);
        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();
            return response()->json(['message' => 'Error al procesar la compra.', 'error' => $e->getMessage()], 500);
        }
    }

    // Método para obtener una factura específica
    public function getInvoiceById($id)
    {
        $invoice = Invoice::with('detailinvoices')->where('IdInvoice', $id)->first();
        if (!$invoice) {
            return response()->json(['message' => 'Factura no encontrada', 'status' => 404], 404);
        }
        return response()->json($invoice, 200);
    }
}
