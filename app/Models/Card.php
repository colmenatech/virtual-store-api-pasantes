<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory; protected $fillable = [
        'id',
        'number',
        'type',
        'user_id'
    ];

    //Relacion con la clase(modelo) de User
    public function users()
    {
     return $this->belongsTo(User::class);
    }
}
