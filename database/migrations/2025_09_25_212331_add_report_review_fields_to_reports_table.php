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
        Schema::table('reports', function (Blueprint $table) {
            $table->text('admin_remarks')->nullable()->after('admin_feedback');
            $table->timestamp('reviewed_at')->nullable()->after('admin_remarks');
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('reviewed_at');
            $table->enum('review_status', ['pending', 'reviewed', 'approved', 'rejected'])->default('pending')->after('reviewed_by');
            
            $table->foreign('reviewed_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Drop foreign key constraint using raw SQL to avoid errors
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'reports' 
                AND COLUMN_NAME = 'reviewed_by' 
                AND CONSTRAINT_NAME != 'PRIMARY'
            ");
            
            foreach ($foreignKeys as $foreignKey) {
                DB::statement("ALTER TABLE reports DROP FOREIGN KEY {$foreignKey->CONSTRAINT_NAME}");
            }
            
            // Drop columns if they exist
            if (Schema::hasColumn('reports', 'admin_remarks')) {
                $table->dropColumn('admin_remarks');
            }
            if (Schema::hasColumn('reports', 'reviewed_at')) {
                $table->dropColumn('reviewed_at');
            }
            if (Schema::hasColumn('reports', 'reviewed_by')) {
                $table->dropColumn('reviewed_by');
            }
            if (Schema::hasColumn('reports', 'review_status')) {
                $table->dropColumn('review_status');
            }
        });
    }
};