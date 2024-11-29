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
           Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // Número de la tarjeta
            $table->string('type'); // Tipo de tarjeta (Visa, MasterCard, etc.)
            $table->foreignId('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            //relacion del campo id de la tabla users a la de cards
            //se define user_id como clave foránea
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
