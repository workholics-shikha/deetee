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
        Schema::create('sales_order_product_operation_details', function (Blueprint $table) {
            $table->id();
            $table->integer("so_id");
            $table->string("so_no",50)->nullable();
            $table->integer("sales_order_product_id");
            $table->integer("product_id");
            $table->integer("sub_product_id");
            $table->integer("operation_id")->nullable();
            $table->string("operation_name")->nullable();
            $table->string("operation_stage")->nullable();
            $table->string("operation_qr_code");
            $table->integer("qty");
            $table->text("processed_qty")->nullable();
            $table->string("operation_status",50)->nullable();
            $table->enum('process_status', ['pending','in-process','completed'])->default('pending');
            $table->enum('final_status', ['pending','in-process','completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_product_operation_details');
    }
};
