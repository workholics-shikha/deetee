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
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');

            // User who will receive the notification
            $table->unsignedBigInteger('machine_id')->nullable()->index();

            // Notification content
            $table->string('title')->nullable();
            $table->text('message')->nullable();

            // Type/category of the notification (optional)
            $table->string('type')->nullable();

            // Read status
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
