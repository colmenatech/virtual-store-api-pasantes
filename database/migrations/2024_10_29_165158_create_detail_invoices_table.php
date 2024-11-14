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
        if (!Schema::hasTable('categories'))
        {
            Schema::create('detailinvoices', function (Blueprint $table) {
            $table->id();  // Llave primaria
            $table->foreignId('invoice_id');
            $table->foreignId('product_id');
            $table->integer('quantity');
            $table->decimal('price', 8, 2); // Asegurarse de que el tamaño decimal esté correcto
            $table->timestamps(); // Añadir los campos created_at y updated_at


            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('restrict');
            //relacion del campo id de la tabla invoice a la de detailinvoice
            //se define invoice_id como clave foránea

        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailinvoices');
    }
};
