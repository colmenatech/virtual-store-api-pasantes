<?php

namespace App\Http\Controllers\Api;

use App\Models\Card;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CardController extends Controller
{

    // Método para listar todas las tarjetas del usuario
    public function index()
    {
        // Obtiene todas las tarjetas asociadas al usuario autenticado
        $cards = auth()->user()->cards;

        // Retorna una respuesta JSON con las tarjetas y un código de estado 200 (OK)
        return response()->json($cards, 200);
    }

    // Método para crear una nueva tarjeta
    public function store(Request $request)
    {
        // Valida los datos recibidos en la solicitud
        // - 'number': requerido, cadena de texto con un máximo de 16 caracteres
        // - 'type': requerido, cadena de texto
        $request->validate([
            'number' => 'required|string|max:16', // Valida que el número de la tarjeta tenga un máximo de 16 caracteres
            'type' => 'required|string',
        ]);

        // Crea una nueva tarjeta asociada al usuario autenticado con los datos proporcionados
        $card = auth()->user()->cards()->create($request->all());

        // Retorna una respuesta JSON con los datos de la nueva tarjeta y un código de estado 201 (Creado)
        return response()->json($card, 201);
    }

    // Método para mostrar los detalles de una tarjeta específica
    public function show($id)
    {
        // Busca la tarjeta por ID entre las tarjetas del usuario autenticado
        $card = auth()->user()->cards()->find($id);

        // Verifica si la tarjeta no se encuentra
        if (!$card) {
            // Retorna una respuesta JSON con un mensaje de error y un código de estado 404 (No encontrado)
            return response()->json(['message' => 'Tarjeta no encontrada.'], 404);
        }

        // Retorna una respuesta JSON con los datos de la tarjeta y un código de estado 200 (OK)
        return response()->json($card, 200);
    }

    // Método para actualizar una tarjeta existente
    public function update(Request $request, $id)
    {
        // Valida los datos recibidos en la solicitud, si están presentes
        // - 'number': a veces requerido, cadena de texto con un máximo de 16 caracteres
        // - 'type': a veces requerido, cadena de texto
        $request->validate([
            'number' => 'sometimes|required|string|max:16',
            'type' => 'sometimes|required|string',
        ]);

        // Busca la tarjeta por ID entre las tarjetas del usuario autenticado
        $card = auth()->user()->cards()->find($id);

        // Verifica si la tarjeta no se encuentra
        if (!$card) {
            // Retorna una respuesta JSON con un mensaje de error y un código de estado 404 (No encontrado)
            return response()->json(['message' => 'Tarjeta no encontrada.'], 404);
        }

        // Actualiza los datos de la tarjeta con los datos proporcionados
        $card->update($request->all());

        // Retorna una respuesta JSON con los datos actualizados de la tarjeta y un código de estado 200 (OK)
        return response()->json($card, 200);
    }

    // Método para eliminar una tarjeta existente
    public function destroy($id)
    {
        // Busca la tarjeta por ID entre las tarjetas del usuario autenticado
        $card = auth()->user()->cards()->find($id);

        // Verifica si la tarjeta no se encuentra
        if (!$card) {
            // Retorna una respuesta JSON con un mensaje de error y un código de estado 404 (No encontrado)
            return response()->json(['message' => 'Tarjeta no encontrada.'], 404);
        }

        // Elimina la tarjeta encontrada
        $card->delete();

        // Retorna una respuesta JSON con un mensaje de éxito y un código de estado 200 (OK)
        return response()->json(['message' => 'Tarjeta eliminada.'], 200);
    }
}
