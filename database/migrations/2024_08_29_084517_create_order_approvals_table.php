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
        Schema::create('order_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_product_id');
            $table->unsignedBigInteger('variation_id');
            $table->integer('quantity');
            $table->foreign('order_product_id')->references('id')->on('shop_order_products');
            $table->foreign('variation_id')->references('id')->on('product_variations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_approvals');
    }
};
