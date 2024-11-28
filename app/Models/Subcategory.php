<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{

    protected $fillable = [
        'id',
        'name',
        'category_id',
        'created_at',
        'updated_at'
    ];

    //Relacion del campo NameSub de subcategories a la tabla products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    //Relacion del campo NameCategory de categories a la tabla subcategories
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function canDelete(): bool
    {
        return $this->products->isEmpty();
    }
    
}
