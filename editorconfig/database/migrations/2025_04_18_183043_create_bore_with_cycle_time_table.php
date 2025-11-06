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
        Schema::create('bore_with_cycle_time', function (Blueprint $table) {
            $table->id();
            $table->integer('operation');
            $table->string('thickness');
            $table->string('0_35');
            $table->string('36_50');
            $table->string('51_75');
            $table->string('76_100');
            $table->string('101_125');
            $table->string('126_150');
            $table->string('0_50');	
            $table->string('0_100');
            $table->string('51_100');	
            $table->string('101_150');	
            $table->string('151_200');	
            $table->string('201_250');	
            $table->string('251_300');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bore_with_cycle_time');
    }
};
