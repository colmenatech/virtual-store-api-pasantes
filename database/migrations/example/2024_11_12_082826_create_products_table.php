<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Llave primaria
            $table->string('name'); // Nombre del producto
            $table->string('description'); // Descripción del producto
            $table->decimal('price', 8, 2); // Precio del producto
            $table->integer('stock'); // Stock del producto
            $table->string('status'); // Estado del producto
            $table->string('image_url')->nullable(); //Imagen del producto
            $table->foreignId('subcategory_id');  // id de la subcategoría

            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('restrict');
            
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
