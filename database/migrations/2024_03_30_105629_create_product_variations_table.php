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
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->string('SKU');
            $table->double('price', $places = 2);
            $table->double('MRP', $places = 2);
            $table->double('weight', $places = 2)->nullable();
            $table->double('height', $places = 2)->nullable();
            $table->double('width', $places = 2)->nullable();
            $table->double('length', $places = 2)->nullable();
            $table->string('color');
            $table->string('size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variation');
    }
};
