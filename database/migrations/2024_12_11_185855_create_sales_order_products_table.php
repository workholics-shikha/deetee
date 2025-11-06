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
        Schema::create('sales_order_products', function (Blueprint $table) {
            $table->id();
            $table->string("so_id");
            $table->string("so_product_qr_code")->nullable();
            $table->string("drawingno")->nullable();
            $table->integer("product_id")->nullable();
            $table->integer("sub_product_id")->nullable();
            $table->integer("item_id")->nullable();
            $table->string("item_name")->nullable();
            $table->string("partno")->nullable();
            $table->string("description")->nullable();
            $table->string("unit")->nullable();
            $table->string("size1")->nullable();
            $table->string("size2")->nullable();
            $table->string("size3")->nullable(); 
            $table->string("material")->nullable();
            $table->string("hardness")->nullable();
            $table->string("measureunit")->nullable();
            $table->integer("quantity")->nullable();
            $table->string("rate")->nullable();
            $table->string("poquantity")->nullable();
            $table->string("porate")->nullable();
            $table->text("additionaloperation")->nullable();
            $table->string("remark")->nullable();
            $table->text("bgroupsheet")->nullable();
            $table->string("pcs")->nullable();
            $table->string("total")->nullable();
            $table->string("totalinr")->nullable();
            $table->string("sequence")->nullable();
            $table->string("cpoid")->nullable();
            $table->string("totalpcs")->nullable();
            $table->string("operation1")->nullable();
            $table->string("operation2")->nullable();
            $table->string("operation3")->nullable();
            $table->string("pass_no")->nullable(); 
            $table->text("pass_sheet")->nullable();
            $table->string("soquantity")->nullable();
            $table->string("sorate")->nullable();
            $table->string("cpoitemid")->nullable();
            $table->string("scr_status",50)->nullable();
            $table->enum('product_status', ['Available','Outsourced','Not Available','Not Applicable'])->default('Available'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_products');
    }
};
