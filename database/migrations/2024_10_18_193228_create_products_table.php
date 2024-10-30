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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Llave primaria
            $table->string('NameProduct'); // Nombre del producto
            $table->string('Description'); // Descripción del producto
            $table->decimal('Price', 8, 2); // Precio del producto
            $table->integer('Stock'); // Stock del producto
            $table->string('NameCategory'); // Nombre de la categoría
            $table->string('NameSub'); // Nombre de la subcategoría
            $table->string('Status'); // Estado del producto
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
