<?php

namespace App\Http\Controllers\Api;

// Importa el controlador base de Laravel
use App\Http\Controllers\Controller;

// Importa la clase Request de Laravel
use Illuminate\Http\Request;

// Importa el modelo User
use App\Models\User;

// Importa la clase Hash para manejar contraseñas
use Illuminate\Support\Facades\Hash;

// Importa la clase Auth para manejar la autenticación
use Illuminate\Support\Facades\Auth;

// Importa la clase Cookie para manejar cookies
use Illuminate\Support\Facades\Cookie;

// Importa la clase Response para manejar respuestas HTTP
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    // Método para registrar un nuevo usuario
    public function register(Request $request) {
        // Validación de los datos de entrada
        $request->validate([
            'name' => 'required', // El nombre es requerido
            'email' => 'required|email|unique:users', // El email es requerido, debe ser un email válido y único en la tabla users
            'password' => 'required|confirmed' // La contraseña es requerida y debe ser confirmada
        ]);

        // Alta del usuario
        $user = new User(); // Crea una nueva instancia del modelo User
        $user->name = $request->name; // Asigna el nombre del request al modelo User
        $user->email = $request->email; // Asigna el email del request al modelo User
        $user->password = Hash::make($request->password); // Hashea la contraseña y la asigna al modelo User
        $user->save(); // Guarda el usuario en la base de datos

        // Respuesta exitosa con el usuario creado
        return response($user, Response::HTTP_CREATED); // Devuelve una respuesta con el usuario creado y el código 201 (Created)
    }

    // Método para iniciar sesión
    public function login(Request $request) {
        // Validación de las credenciales de entrada
        $credentials = $request->validate([
            'email' => ['required', 'email'], // El email es requerido y debe ser un email válido
            'password' => ['required'] // La contraseña es requerida
        ]);

        // Intento de autenticación
        if (Auth::attempt($credentials)) { // Verifica si las credenciales son correctas
            $user = Auth::user(); // Obtiene el usuario autenticado
            $token = $user->createToken('token')->plainTextToken; // Crea un token de acceso y lo convierte a texto plano
            $cookie = cookie('cookie_token', $token, 60 * 24); // (Opcional) Crea una cookie con el token que dura 24 horas

            // Respuesta exitosa con el token generado
            return response(["token" => $token], Response::HTTP_OK)->withCookie($cookie); // Devuelve el token en la respuesta con una cookie
        } else {
            // Respuesta de error si las credenciales son inválidas
            return response(["message" => "Credenciales inválidas"], Response::HTTP_UNAUTHORIZED); // Devuelve una respuesta de error con el código 401 (Unauthorized)
        }
    }

    // Método para obtener el perfil del usuario autenticado
    public function userProfile(Request $request) {
        return response()->json([
            "message" => "userProfile OK", // Mensaje de confirmación
            "userData" => auth()->user() // Devuelve los datos del usuario autenticado
        ], Response::HTTP_OK); // Devuelve la respuesta con el código 200 (OK)
    }

    // Método para cerrar sesión
    public function logout() {
        $cookie = Cookie::forget('cookie_token'); // Borra la cookie del token
        return response(["message" => "Cierre de sesión OK"], Response::HTTP_OK)->withCookie($cookie); // Devuelve una respuesta de confirmación con una cookie eliminada
    }

    // Método para obtener todos los usuarios
    public function allUsers() {
        $users = User::all(); // Obtiene todos los usuarios de la base de datos
        return response()->json([
            "users" => $users // Devuelve la lista de usuarios en formato JSON
        ]);
    }
}
