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
        // The 'code' and 'native_name' columns do not exist, so these lines are commented out to prevent errors.
        // $table->dropUnique(['code']);
        // $table->dropColumn(['code', 'native_name']);
        // No action needed since columns/index do not exist.
        Schema::table('languages', function (Blueprint $table) {
            // ...existing code...
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('languages', function (Blueprint $table) {
            //
        });
    }
};
