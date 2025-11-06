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
        Schema::create('sub_product', function (Blueprint $table) {
            $table->id();
            $table->integer('product_master_id');
            $table->text('sub_product_name'); 
            $table->enum('product_flow', ['Available','Outsourced','Not Available','Not Applicable'])->default('Available');
            $table->enum('cycle_flow', ['Available','Outsourced','Not Available','Not Applicable'])->default('Available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_product');
    }
};
