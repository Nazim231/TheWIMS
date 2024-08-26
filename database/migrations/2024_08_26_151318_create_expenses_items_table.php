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
        Schema::create('expenses_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expenses_id');
            $table->unsignedBigInteger('variation_id');
            $table->integer('quantity');
            $table->decimal('price');
            $table->decimal('total_price');
            $table->foreign('expenses_id')->references('id')->on('expenses');
            $table->foreign('variation_id')->references('id')->on('product_variations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses_items');
    }
};
