<?php

use App\Models\Item;
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
        Schema::create('sale_information', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Item::class);
            $table->string('selling_price');
            $table->string('sale_description')->nullable();
            $table->string('sale_tax')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_information');
    }
};
