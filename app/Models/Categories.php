<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'IdCategory',
        'NameCategory'
    ];

    //Relación del campo NameCategory con products
    public function products()
    {
        return $this->hasMany(Products::class, 'NameCategory', 'NameCategory');
    }

     //Relación del campo NameCategory con subcategories
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class, 'NameCategory', 'NameCategory');
    }
}
