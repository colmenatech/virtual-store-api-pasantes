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
        if (!Schema::hasTable('products')) {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Llave primaria
            $table->string('name'); // Nombre del producto
            $table->string('description'); // Descripción del producto
            $table->decimal('price', 8, 2); // Precio del producto
            $table->integer('stock'); // Stock del producto
            $table->foreignId('subcategory_id');  // id de la subcategoría
            $table->string('status'); // Estado del producto
            $table->string('image_url')->nullable(); //Imagen del producto

            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('restrict');
            //relacion del campo id de la tabla subcategorias a la de products
            //se define subcategory_id como clave foránea

            $table->timestamps(); // Campos created_at y updated_at
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
