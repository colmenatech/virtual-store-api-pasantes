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
                $table->string('NameSub'); // Columna de nombre de subcategoría
                $table->unsignedBigInteger('IdCategory'); // Columna de ID de la categoría
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
