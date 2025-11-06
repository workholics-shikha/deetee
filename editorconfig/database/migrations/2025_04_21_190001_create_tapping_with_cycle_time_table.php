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
        Schema::create('tapping_with_cycle_time', function (Blueprint $table) {
            $table->id();
            $table->integer('operation');
            $table->string('size');
            $table->string('length');
            $table->string('cycle_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tapping_with_cycle_time');
    }
};
