<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    // Array de mensajes
    private $messages = [
    'not_found' => ['message' => 'No se encontró el historial de compras.', 'status' => 404],
    'created' => ['message' => 'Historial de compras creado exitosamente.', 'status' => 201],
    'validation_error' => ['message' => 'Error en la validación de los datos.', 'status' => 400],
    'creation_error' => ['message' => 'Error al crear el historial de compras.', 'status' => 500],
    'deleted' => ['message' => 'Historial de compras eliminado exitosamente.', 'status' => 200],
    'found' => ['message' => 'Historial de compras encontrado.', 'status' => 200]
  ];


   // MÉTODO PARA OBTENER TODO EL HISTORIAL DE COMPRAS
    public function index()
    {
        // Obtener todo el historial de compras
        $invoice = Invoice::all();

        // Verificar si no se encontró el historial de compras
        if ($invoice->isEmpty()) {
            // Retornar respuesta JSON con mensaje de "No se encontró el historial de compras" y código de estado 404
            return response()->json($this->messages['not_found'], 404);
        }

        // Preparar y retornar respuesta JSON con el historial de compras encontrado y mensaje de éxito
        return response()->json([
            'message' => $this->messages['found']['message'], // Mensaje de éxito
            'invoice' => $invoice, // Lista de historial de compras encontrado
            'status' => $this->messages['found']['status'], // Código de estado 200
        ], 200);
    }

    // MÉTODO PARA OBTENER UN HISTORIAL DE COMPRAS POR ID
    public function show($id)
    {
        // Buscar el historial de compras por ID
        $invoice = Invoice::find($id);

        // Verificar si el historial de compras no se encuentra
        if (!$invoice) {
            // Retornar respuesta JSON con mensaje de "No se encontró el historial de compras" y código de estado 404
            return response()->json($this->messages['not_found'], 404);
        }

        // Preparar y retornar respuesta JSON con el historial de compras encontrado y mensaje de éxito
        return response()->json([
            'message' => $this->messages['found']['message'], // Mensaje de éxito
            'invoice' => $invoice, // Historial de compras encontrado
            'status' => $this->messages['found']['status'], // Código de estado 200
        ], 200);
    }
}
