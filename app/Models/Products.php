<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'NameProduct',
        'Description',
        'Price',
        'Stock',
       'NameCategory', // Actualizado aquí
        'NameSub',
        'ImageURL', // Añadir el nuevo campo aquí
        'Status'
    ];

    //Relación del campo NameCategory de la tabla categories a la de products
    public function category()
    {
        return $this->belongsTo(Category::class, 'NameCategory', 'NameCategory');
    }

     //Relación del campo NameSub de la tabla categories a la de products
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'NameSub', 'NameSub');
    }
}
