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
        Schema::create('product_masters', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('unit_number');
            $table->string('unit', 10);
            $table->string('group', 10);
            $table->string('erp_product', 100);
            $table->string('erp_nomenclature', 100);
            $table->string('product_modified_name')->default(0);
            $table->text('product_qr_code')->default('NA');
            $table->enum('status', ['Working', 'Maintenance', 'Available'])->default('Available');
            $table->enum('product_flow', ['Available', 'Outsourced', 'Not Available', 'Not Applicable'])->default('Available');
            $table->enum('cycle_flow', ['Available', 'Outsourced', 'Not Available', 'Not Applicable'])->default('Available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_masters');
    }
};
