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
        Schema::create('sales_order_trackings', function (Blueprint $table) {
            $table->id();
            $table->integer('so_id');
            $table->integer('so_product_id');
            $table->integer('sub_product_id');
            $table->integer('machine_id');
            $table->integer('operator_id');
            $table->integer('pass_id')->nullable();
            $table->integer('operation_id');
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time')->nullable();
            $table->integer('time_taken')->nullable();
            $table->integer('total_quantity')->nullable();
            $table->integer('quantity_processed')->nullable();
            $table->string('reason', 255)->nullable();
            $table->string('roll_status', 50)->nullable();
            $table->string('final_status', 50)->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_trackings');
    }
};
