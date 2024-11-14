<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'total',
        'created_at',
        'updated_at'
    ];

    public function detailinvoices()
    {
        return $this->hasMany(DetailInvoice::class);
    }
}
