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
        // Verificar si la tabla 'categories' no existe antes de crearla
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('NameCategory', 50); // Columna de nombre de categoría
                $table->timestamps();
            });
        }
    }
    
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
