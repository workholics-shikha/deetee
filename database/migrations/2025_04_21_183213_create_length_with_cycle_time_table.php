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
        Schema::create('length_with_cycle_time', function (Blueprint $table) {
            $table->id();
            $table->integer('operation');
            $table->string('diameter');
            $table->string('0_40');
            $table->string('41_50');
            $table->string('51_60');
            $table->string('61_70');
            $table->string('71_80');
            $table->string('81_90');
            $table->string('91_100');
            $table->string('101_110');
            $table->string('111_120');
            $table->string('121_130');
            $table->string('131_140');
            $table->string('141_150');
            $table->string('151_160');
            $table->string('161_170');
            $table->string('171_180');
            $table->string('181_190');
            $table->string('191_200');
            $table->string('201_210');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('length_with_cycle_time');
    }
};
