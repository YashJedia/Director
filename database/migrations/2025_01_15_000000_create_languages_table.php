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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // English name (e.g., "Spanish")
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->unsignedBigInteger('assigned_user_id')->nullable();
            $table->unsignedBigInteger('assigned_admin_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('assigned_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assigned_admin_id')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
}; 