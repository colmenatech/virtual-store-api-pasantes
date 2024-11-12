<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $fillable = [
        'id',
        'name',
        'category_id'
    ];

    public function products()
    {
        return $this->hasMany(Products::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
