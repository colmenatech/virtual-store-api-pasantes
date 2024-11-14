<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'stock',
        'subcategory_id',
        'status',
        'image_url'
    ];

     // Relación con la subcategoría
     public function subcategory()
     {
         return $this->belongsTo(Subcategory::class);
     }

}
