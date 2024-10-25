<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subcategories;

class subcategoriesController extends Controller
{
    // Array de mensajes
    private $messages = [
        'not_found' => ['message' => 'No se encontró la subcategoría.', 'status' => 404],
        'created' => ['message' => 'Subcategoría creada exitosamente.', 'status' => 201],
        'validation_error' => ['message' => 'Error en la validación de los datos.', 'status' => 400],
        'creation_error' => ['message' => 'Error al crear la subcategoría.', 'status' => 500],
        'deleted' => ['message' => 'Subcategoría eliminada exitosamente.', 'status' => 200],
        'found' => ['message' => 'Subcategorías encontradas.', 'status' => 200],
    ];

    // MÉTODO PARA OBTENER TODAS LAS SUBCATEGORÍAS
    public function index()
    {
        // Obtener todas las subcategorías
        $subcategories = Subcategories::all();

        // Verificar si no se encontraron subcategorías
        if ($subcategories->isEmpty()) {
            // Retornar respuesta JSON con mensaje de "No se encontraron subcategorías" y código de estado 404
            return response()->json($this->messages['not_found'], 404);
        }

        // Preparar y retornar respuesta JSON con las subcategorías encontradas y mensaje de éxito
        return response()->json([
            'message' => $this->messages['found']['message'], // Mensaje de éxito
            'subcategories' => $subcategories, // Lista de subcategorías encontradas
            'status' => $this->messages['found']['status'], // Código de estado 200
        ], 200);
    }


     // MÉTODO PARA OBTENER UNA SUBCATEGORÍA POR ID
    public function show($id)
    {
        // Buscar la subcategoría por ID
        $subcategory = Subcategories::find($id);

        // Verificar si la subcategoría no se encuentra
        if (!$subcategory) {
            // Retornar respuesta JSON con mensaje de "No se encontró la subcategoría" y código de estado 404
            return response()->json($this->messages['not_found'], 404);
        }

        // Preparar y retornar respuesta JSON con la subcategoría encontrada y mensaje de éxito
        return response()->json([
            'message' => $this->messages['found']['message'], // Mensaje de éxito
            'subcategory' => $subcategory, // Subcategoría encontrada
            'status' => $this->messages['found']['status'], // Código de estado 200
        ], 200);
    }

    public function store(Request $request)
    {
        // Validar los datos del request
        $validator = Validator::make($request->all(), [
            'NameSub' => 'required|string|max:50', // Asegúrate de que el campo sea el mismo que en tu solicitud
        ]);
    
        // Verificar si la validación falla
        if ($validator->fails()) {
            return response()->json(array_merge($this->messages['validation_error'], ['errors' => $validator->errors()]), 400);
        }
    
        // Crear la nueva subcategoría
        $subcategory = Subcategories::create([
            'NameSub' => $request->NameSub, // Asegúrate de que el campo sea el mismo que en tu solicitud
            'IdCategory' => $request->IdCategory,
        ]);
    
        // Verificar si la creación falla
        if (!$subcategory) {
            return response()->json($this->messages['creation_error'], 500);
        }
    
        // Retornar respuesta exitosa en formato JSON con el mensaje de creación exitosa y datos de la subcategoría
        return response()->json(array_merge($this->messages['created'], ['subcategory' => $subcategory]), 201);
    }
    
    // MÉTODO PARA ELIMINAR UNA SUBCATEGORÍA
    public function destroy($id)
    {
        // Buscar la subcategoría por ID
        $subcategory = Subcategories::find($id);

        // Verificar si la subcategoría no se encuentra
        if (!$subcategory) {
            // Retornar respuesta JSON con mensaje de "No se encontró la subcategoría" y código de estado 404
            return response()->json($this->messages['not_found'], 404);
        }

        // Eliminar la subcategoría encontrada
        $subcategory->delete();

        // Retornar respuesta JSON con mensaje de éxito y código de estado 200
        return response()->json($this->messages['deleted'], 200);
    }

}
