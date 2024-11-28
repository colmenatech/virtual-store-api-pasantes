<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Comentado, se usaría para verificar el email del usuario

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; //un trait es una forma de reusar código en múltiples clases

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles; // Incluye HasApiTokens aquí


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ["name", "email", "password"];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ["password", "remember_token"];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    // Relación con el modelo Invoice (facturas)
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // Método para verificar si el usuario se puede eliminar
    public function canDelete(): bool
    {
        // Verificar si el usuario tiene facturas
        if ($this->invoices()->exists()) {
            return false; // No se puede eliminar el usuario porque tiene facturas
        }

        // Verificar si el usuario tiene tarjetas
        if ($this->cards()->exists()) {
            return false; // No se puede eliminar el usuario porque tiene tarjetas
        }

        return true; // No tiene facturas ni tarjetas, se puede eliminar
    }

    // Método para eliminar todas las tarjetas del usuario
    public function deleteCards()
    {
        foreach ($this->cards as $card) {
            $card->delete();
        }
    }
    
}
