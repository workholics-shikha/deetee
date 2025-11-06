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
        Schema::create('material_with_cycle_time', function (Blueprint $table) {
            $table->id();
            $table->integer('operation');
            $table->string('diameter');
            $table->integer('d2');
            $table->integer('d3');
            $table->integer('en31');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_with_cycle_time');
    }
};
