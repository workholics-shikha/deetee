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
        Schema::create('corners_with_cycle_time', function (Blueprint $table) {
            $table->id();
            $table->integer('operation');
            $table->integer('corner');
            $table->integer('cycle_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corners_with_cycle_time');
    }
};
