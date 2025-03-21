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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->string('unit')->nullable();
            $table->boolean('returnable')->default(true);
            $table->string('images')->nullable();
            $table->string('brand')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('mpn')->nullable();
            $table->string('isbn')->nullable();
            $table->string('upc')->nullable();
            $table->string('ean')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
