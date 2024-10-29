<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoice';

    protected $fillable = [
        'IdInvoice',
        'IdUser',
        'Total',
        'CreatedAt'
    ];

    public function detailinvoices()
    {
        return $this->hasMany(DetailInvoice::class, 'IdInvoice');
    }
}
