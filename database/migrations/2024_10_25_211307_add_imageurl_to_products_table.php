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
        // Verificar si la columna `ImageURL` ya existe
        if (!Schema::hasColumn('products', 'ImageURL')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('ImageURL')->nullable()->after('Status'); // Añadir la columna ImageURL solo si no existe
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar la columna `ImageURL` solo si existe
        if (Schema::hasColumn('products', 'ImageURL')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('ImageURL'); // Eliminar la columna ImageURL
            });
        }
    }
};
