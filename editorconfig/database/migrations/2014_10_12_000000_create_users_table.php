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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('email',100)->nullable();
            $table->string('phone',25)->nullable();
            $table->string('username')->nullable();
            $table->integer('role')->default('2'); 
            $table->string('department',20)->nullable();  
            $table->string('designation',20)->nullable();  
            $table->tinyText('unit')->nullable(); 
            $table->tinyText('unit_name')->nullable(); 
            $table->string('employee_group',10)->nullable();
            $table->string('shift',15)->nullable();
            $table->string('user_qr_code')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
