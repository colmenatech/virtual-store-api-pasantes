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
        // Verificar si la tabla 'subcategories' no existe antes de crearla
        if (!Schema::hasTable('subcategories')) {
            // Crear la tabla 'subcategories'
            Schema::create('subcategories', function (Blueprint $table) {
                $table->id(); // Columna ID con auto-incremento
                $table->string('name'); // Columna de nombre de subcategoría
                $table->foreignId('category_id');  // id de la categoría

                $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');

                $table->timestamps(); // Añadir columnas created_at y updated_at para gestionar automáticamente las marcas de tiempo
            });
        }
    }

    public function down(): void
    {
        // Eliminar la tabla 'subcategories' si existe
        Schema::dropIfExists('subcategories');
    }
};
