<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detailinvoice', function (Blueprint $table) {
            $table->increments('IdDetail'); // Llave primaria
            $table->integer('IdInvoice')->unsigned();
            $table->integer('IdProduct')->unsigned();
            $table->integer('Quantity');
            $table->decimal('Price', 8, 2); // Asegurarse de que el tamaño decimal esté correcto
            $table->timestamps(); // Añadir los campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailinvoice');
    }
};
