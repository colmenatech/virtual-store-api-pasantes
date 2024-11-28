<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /*public function __construct()
    {
        $this->middleware('role:admin')->except(['index', 'show']);
        $this->middleware('role:client')->only(['index', 'show']);
    }*/

    // Array de mensajes
    private $messages = [
        'found' => ['message' => 'Categorías encontradas.', 'status' => 200], // Añadir mensaje de encontrado exitosamente
        'not_found' => ['message' => 'No se encontró la categoría.', 'status' => 404],
        'created' => ['message' => 'Categoría creada exitosamente.', 'status' => 201],
        'validation_error' => ['message' => 'Error en la validación de los datos.', 'status' => 400],
        'creation_error' => ['message' => 'Error al crear la categoría.', 'status' => 500],
        'deleted' => ['message' => 'Categoría eliminada exitosamente.', 'status' => 200], // Añadir mensaje de eliminado exitosamente
        'not_delete' => ['message' => 'La categoría no puede ser eliminada, posee productos asociados', 'status' => 404],

];

// MÉTODO PARA OBTENER TODAS LAS CATEGORÍAS
public function index()
{
    // Obtener todas las categorías
    $categories = Category::all();

    // Verificar si no se encontraron categorías
    if ($categories->isEmpty()) {
        // Retornar respuesta JSON con mensaje de "No se encontraron categorías" y código de estado 404
        return response()->json($this->messages['not_found'], 404);
    }

    // Preparar y retornar respuesta JSON con las categorías encontradas y mensaje de éxito
    return response()->json([
        'message' => $this->messages['found']['message'], // Mensaje de éxito
        'categories' => $categories, // Lista de categorías encontradas
        'status' => $this->messages['found']['status'], // Código de estado 200
    ], 200);
}

    // Método para mostrar una categoría específica
    public function show($id)
    {
        // Buscar la categoría por ID
        $category = Category::find($id);

        // Verificar si la categoría no se encuentra
        if (!$category) {
            return response()->json($this->messages['not_found'], 404);
        }

        // Preparar y retornar respuesta JSON con la categoría encontrada y mensaje de éxito
        return response()->json([
            'message' => $this->messages['found']['message'],
            'category' => $category,
            'status' => $this->messages['found']['status'],
        ], 200);
    }




   // MÉTODO PARA CREAR UNA NUEVA CATEGORIA
   public function store(Request $request)
   {
        // Validar los datos del request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50', // El nombre de la categoría es requerido y debe ser una cadena con un máximo de 50 caracteres
        ]);

        // Verificar si la validación falla
        if ($validator->fails()) {
            // Retornar respuesta de error en formato JSON con el mensaje de validación de datos
            return response()->json(array_merge($this->messages['validation_error'], ['errors' => $validator->errors()]), 400);
        }

        // Crear la nueva categoría
        $category = Category::create([
            'name' => $request->name, // Asignar el nombre de la categoría del request
        ]);

        // Verificar si la creación falla
        if (!$category) {
            // Retornar respuesta de error en formato JSON con mensaje de error en la creación
            return response()->json($this->messages['creation_error'], 500);
        }

        // Retornar respuesta exitosa en formato JSON con el mensaje de creación exitosa y datos de la categoría
        return response()->json(array_merge($this->messages['created'], ['category' => $category]), 201);
    }


    // MÉTODO PARA ACTUALIZAR UNA CATEGORIA
    public function update(Request $request, $id)
    {
        // Buscar la categoría por ID
        $category = Category::find($id);

        // Verificar si la categoría no se encuentra
        if (!$category) {
            $data = $this->messages['not_found'];
            // Retornar respuesta de error en formato JSON con código 404 Not Found
            return response()->json($data, 404);
        }

        // Validar los datos del request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
        ]);

        // Verificar si la validación falla
        if ($validator->fails()) {
            $data = array_merge($this->messages['validation_error'], ['errors' => $validator->errors()]);
            // Retornar respuesta de error en formato JSON con código 400 Bad Request
            return response()->json($data, 400);
        }

        // Actualizar los datos de la categoría
        $category->name = $request->name;
        $category->save();

        // Preparar la respuesta exitosa
        $data = [
            'message' => 'Categoría actualizada',
            'category' => $category,
            'status' => 200
        ];

        // Retornar la respuesta en formato JSON con código 200 OK
        return response()->json($data, 200);
    }


    public function destroy($id)
    {
        // Buscar la categoría por ID
        $category = Category::find($id);
    
        // Verificar si la categoría no se encuentra
        if (!$category) {
            return response()->json($this->messages['not_found'], 404);
        }
    
        // Verificar si se puede eliminar la categoría
        if (!$category->canDelete()) {
            return response()->json($this->messages['not_delete'], 404);
        }
    
        // Eliminar las subcategorías asociadas
        foreach ($category->subcategories as $subcategory) {
            $subcategory->delete();
        }
    
        // Eliminar la categoría encontrada
        $category->delete();
    
        return response()->json($this->messages['deleted'], 200);
    }
}
