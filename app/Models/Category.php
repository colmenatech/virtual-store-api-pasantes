<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at'
    ];

    //Relación del campo NameCategory con products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

     //Relación del campo NameCategory con subcategories
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function canDelete(): bool
    {
      // Verificar si la categoría tiene subcategorías
        if ($this->subcategories->isEmpty()) {
            // Si la colección de subcategorías está vacía, significa que esta categoría no tiene subcategorías asociadas.
            // En este caso, se puede eliminar la categoría sin problemas.
            return true; // La categoría no tiene subcategorías y se puede eliminar
        }

        // Iterar sobre cada subcategoría asociada a esta categoría
        foreach ($this->subcategories as $subcategory) {
            // Para cada subcategoría, verificar si tiene productos asociados.
            // Esto se hace llamando al método products()->exists(), que retorna true si existen productos asociados a la subcategoría.
            if ($subcategory->products()->exists()) {
                // Si encontramos al menos una subcategoría que tiene productos, no podemos eliminar la categoría.
                return false; // Encontramos una subcategoría con productos, no se puede eliminar
            }
        }

        // Si llegamos a este punto, significa que todas las subcategorías están vacías (sin productos asociados).
        // En este caso, es seguro eliminar la categoría.
        return true; // No se encontraron subcategorías con productos, se puede eliminar
  }
}
