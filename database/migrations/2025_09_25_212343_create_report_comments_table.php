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
        Schema::create('report_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');
            $table->unsignedBigInteger('admin_id');
            $table->string('section'); // e.g., 'goal_progress', 'organic_reach', etc.
            $table->string('field'); // e.g., 'languages_goal_2025', 'facebook_reach', etc.
            $table->text('comment');
            $table->boolean('is_reply')->default(false);
            $table->unsignedBigInteger('parent_comment_id')->nullable();
            $table->timestamps();

            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('parent_comment_id')->references('id')->on('report_comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_comments');
    }
};