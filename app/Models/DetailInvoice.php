<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailInvoice extends Model
{
    use HasFactory;

    protected $table = 'detailinvoice';

    protected $fillable = [
        'id',
        'invoice_id',
        'product_id',
        'quantity',
        'price',
        'created_at',
        'updated_at'
    ];

    // Relación con la tabla invoice
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Relación con la tabla product
    public function products()
    {
        return $this->belongsTo(Products::class);
    }

}
