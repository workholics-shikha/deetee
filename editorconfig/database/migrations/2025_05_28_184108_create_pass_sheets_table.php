<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pass_sheets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cpoitemid');
            $table->integer('sr_no')->nullable();
            $table->string('pass_no')->nullable();
            $table->string('mrk_pass_no')->nullable();
            $table->string('drawing_no')->nullable();
            $table->string('size1')->nullable();
            $table->string('size2')->nullable();
            $table->string('size3')->nullable();
            $table->string('qty')->nullable(); // change to integer if it's always numeric
            $table->string('material')->nullable();
            $table->string('hardness')->nullable();
            $table->string('fin_wt')->nullable();
            $table->string('bs1_dia')->nullable();
            $table->string('bs1_depth')->nullable();
            $table->string('bs1_bore')->nullable();
            $table->string('bs2_dia')->nullable();
            $table->string('bs2_depth')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('revisioncount')->default(0);
            $table->string('pass_sheet_qr_code')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pass_sheets');
    }
};
