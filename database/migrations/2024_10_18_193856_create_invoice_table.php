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
        Schema::create('invoice', function (Blueprint $table) {
            $table->increments('id'); // Llave primaria
            $table->integer('user_id')->unsigned();
            $table->decimal('total', 8, 2); // Asegurarse de que el tamaño decimal esté correcto
            $table->timestamps(); // Añadir los campos created_at y updated_at
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
