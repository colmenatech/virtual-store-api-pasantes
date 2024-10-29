<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Comentado, se usaría para verificar el email del usuario

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; //un trait es una forma de reusar código en múltiples clases


// Clase User que extiende de Authenticatable, permitiendo autenticación
class User extends Authenticatable
{
    use HasRoles;
    use HasApiTokens, HasFactory, Notifiable; // Traits usados para diversas funcionalidades

    // Especifica el nombre de la tabla asociada con el modelo
    protected $table = 'users';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    // Campos que se ocultarán cuando se convierta el modelo a array o JSON
    protected $hidden = [
        'password'
    ];
}
