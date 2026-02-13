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
        Schema::create('width_with_cycle_time', function (Blueprint $table) {
            $table->id();
            $table->integer('operation');
            $table->string('thickness');
            $table->string('0_250');
            $table->string('251_500');
            $table->string('501_750');
            $table->string('751_1000');
            $table->string('0_50');
            $table->string('51_151');
            $table->string('0_100');
            $table->string('101_150');
            $table->string('151_200');
            $table->string('201_300');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('width_with_cycle_time');
    }
};
