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
        Schema::create('machine_health_monitorings', function (Blueprint $table) {
            $table->id();
            $table->integer('master_id');
            $table->date('start_date_time');
            $table->date('end_date_time')->nullable();
            $table->string('reason', 255)->nullable();
            $table->string('monitor_for')->comment('maintenance, breakdown');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_health_monitorings');
    }
};
