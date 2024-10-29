<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailInvoice extends Model
{
    use HasFactory;

    protected $table = 'detailinvoice';

    protected $fillable = [
        'IdInvoice',
        'IdProduct',
        'Quantity',
        'Price',
    ];

    // Relación con la tabla `invoice`
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'IdInvoice');
    }

    // Relación con la tabla `product`
    public function products()
    {
        return $this->belongsTo(Products::class, 'IdProduct');
    }

}
