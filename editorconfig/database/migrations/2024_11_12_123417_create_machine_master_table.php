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
        Schema::create('machine_master', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('unit_number');
            $table->string('unit_name', 10);
            $table->string('machine', 100);
            $table->string('machine_image', 100)->nullable();
            $table->string('machine_type', 100);
            $table->string('section', 100);
            $table->string('sub_section', 100);
            $table->integer('operator_id')->nullable();
            $table->integer('tracking_id')->nullable();
            $table->text('machine_qr_code')->default('NA');
            $table->enum('machine_status', ['active', 'maintenance', 'in-working', 'breakdown'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_master');
    }
};
