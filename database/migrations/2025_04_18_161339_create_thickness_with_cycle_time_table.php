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
        Schema::create('thickness_with_cycle_time', function (Blueprint $table) {
            $table->id();
            $table->integer('operation');
            $table->string('thickness');
            $table->integer('cycle_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thickness_with_cycle_time');
    }
};
