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
        Schema::create('operator_attendances', function (Blueprint $table) {
            $table->id();
            $table->integer('operator_id');
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time')->nullable();
            $table->integer('machine_id')->nullable();
            $table->integer('so_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operator_attendances');
    }
};
