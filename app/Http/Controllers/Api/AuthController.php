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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\HasApiTokens;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verificar si la validación falla
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'status' => 422], 422);
        }

        // Alta del usuario
        $user = new User(); // Crea una nueva instancia del modelo User
        $user->name = $request->name; // Asigna el nombre del request al modelo User
        $user->email = $request->email; // Asigna el email del request al modelo User
        $user->password = Hash::make($request->password); // Hashea la contraseña y la asigna al modelo User
        $user->save(); // Guarda el usuario en la base de datos

        // Asignar el rol de cliente al nuevo usuario
        $user->assignRole('client');

        // Respuesta exitosa con el usuario creado
        return response()->json(['message' => 'Usuario registrado exitosamente.', 'user' => $user, 'status' => Response::HTTP_CREATED], Response::HTTP_CREATED);
    }

    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        // Autenticación exitosa, generar el token
        $user = Auth::user();
        $token = $user->createToken('YourAppName')->plainTextToken;

        // Obtener los permisos del usuario (ejemplo usando Gates)
        $permissions = Gate::forUser($user)->abilities();

        // Obtener el rol del usuario
        $roles = $user->getRoleNames(); // Retorna una colección de nombres de roles

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'roles' => $roles,
            ],

        ]);
    }

    return response()->json(['error' => 'Unauthorized'], 401);
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

   // Método para eliminar un usuario
   public function destroy($id)
   {
       // Buscar el usuario por ID
       $user = User::find($id);

       // Verificar si el usuario no se encuentra
       if (!$user) {
           return response()->json(['message' => 'Usuario no encontrado.'], 404);
       }

       // Verificar si el usuario tiene facturas
       if ($user->invoices()->exists()) {
           return response()->json(['message' => 'No se puede eliminar el usuario porque tiene facturas.'], 403);
       }

       // Eliminar las tarjetas del usuario si las tiene
       if ($user->cards()->exists()) {
           $user->deleteCards();
       }

       // Eliminar el usuario encontrado
       $user->delete();

       return response()->json(['message' => 'Usuario y sus tarjetas eliminados. Sesión cerrada.'], 200);
   }
}
