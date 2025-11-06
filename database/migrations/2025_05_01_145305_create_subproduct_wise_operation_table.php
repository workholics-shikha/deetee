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
        Schema::create('subproduct_wise_operation', function (Blueprint $table) {
            $table->id();
            $table->string('product_master_id');
            $table->string('subproduct_id');
            $table->string('operation_id');
            $table->string('operation_name');
            $table->string('unit');    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subproduct_wise_operation');
    }
};
