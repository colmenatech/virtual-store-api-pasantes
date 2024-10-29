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
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('IdInvoice'); // Llave primaria
            $table->integer('IdUser')->unsigned();
            $table->decimal('Total', 8, 2); // Asegurarse de que el tamaño decimal esté correcto
            $table->timestamps(); // Añadir los campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
