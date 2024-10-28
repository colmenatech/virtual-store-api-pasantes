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
        Schema::create("products", function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD:database/migrations/2024_10_16_223821_create_products_table.php
            $table->string("name");
            $table->string("description");
            $table->string("price");
            $table->string("stock");
            $table->string("category_id");
            $table->string("status");
=======
            $table->string('NameProduct');
            $table->string('Description');
            $table->string('Price');
            $table->string('Stock');
            $table->string('IdCategory');
            $table->string('IdSubcategory');
            $table->string('Status');
>>>>>>> Crud-Api:database/migrations/2024_10_18_193228_create_products_table.php
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("products");
    }
};
