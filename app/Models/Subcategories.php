<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategories extends Model
{
    protected $table = 'subcategories';

    protected $fillable = [
        'IdSubcategory',
        'NameSub',
        'NameCategory'
    ];

    //Relacion del campo NameSub de subcategories a la tabla products
    public function products()
    {
        return $this->hasMany(Products::class, 'NameSub', 'NameSub');
    }

    //Relacion del campo NameCategory de categories a la tabla subcategories
    public function category()
    {
        return $this->belongsTo(Categories::class, 'NameCategory', 'NameCategory');
    }
}
