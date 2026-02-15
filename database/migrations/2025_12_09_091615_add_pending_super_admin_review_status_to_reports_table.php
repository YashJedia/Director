<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite does not support MODIFY COLUMN or ENUM types. Use string column for status.
        Schema::table('reports', function (Blueprint $table) {
            $table->string('status')->default('draft')->change();
        });
        // Note: Application logic should enforce allowed status values.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to string column with default 'draft'.
        Schema::table('reports', function (Blueprint $table) {
            $table->string('status')->default('draft')->change();
        });
    }
};
