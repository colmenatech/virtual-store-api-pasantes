<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = "products";

    protected $fillable = [
       'NameProduct',
        'Description',
        'Price',
        'Stock',
        'NameSub', // Nombre de la subcategoría
        'ImageURL',
        'Status'
    ];


    // Relación con la subcategoría
    public function subcategory()
    {
        return $this->belongsTo(Subcategories::class, 'NameSub', 'NameSub');
    }

}
