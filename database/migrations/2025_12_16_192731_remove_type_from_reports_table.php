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
        // SQLite does not support dropping constraints directly. Only drop the 'type' column.
        // Only attempt to drop 'type' column if it exists
        if (Schema::hasColumn('reports', 'type')) {
            Schema::table('reports', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
        
        if (!empty($constraintExists)) {
            DB::statement('ALTER TABLE reports DROP INDEX unique_user_language_report');
        }
        
        // Drop the type column if it exists
        if (Schema::hasColumn('reports', 'type')) {
            Schema::table('reports', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
        
        // Recreate foreign keys
        Schema::table('reports', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            
            // Recreate reviewed_by foreign key if the column exists
            if (Schema::hasColumn('reports', 'reviewed_by')) {
                $table->foreign('reviewed_by')->references('id')->on('admins')->onDelete('set null');
            }
        });
        
        // Create new unique constraint without type
        // Only create unique index if it does not already exist
        // SQLite does not support checking index existence directly, so skip if error occurs
        try {
            Schema::table('reports', function (Blueprint $table) {
                $table->unique(['user_id', 'language_id', 'quarter'], 'unique_user_language_quarter');
            });
        } catch (\Exception $e) {
            // Index already exists, skip
        }
        
        // SQLite does not support SET FOREIGN_KEY_CHECKS, so skip this step.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Drop the new unique constraint
            $table->dropUnique('unique_user_language_quarter');
            
            // Add type column back
            $table->enum('type', ['Quarterly Progress', 'Quarterly Summary', 'Quarterly Review'])->after('title');
            
            // Restore original unique constraint
            $table->unique(['user_id', 'language_id', 'type', 'quarter'], 'unique_user_language_report');
        });
    }
};
