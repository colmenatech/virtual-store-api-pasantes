<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shoppingcart', function (Blueprint $table) {
            $table->integer('IdCart', true);
            $table->integer('IdUser');
            $table->integer('IdProduct');
            $table->integer('Quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shoppingcart');
    }
};
