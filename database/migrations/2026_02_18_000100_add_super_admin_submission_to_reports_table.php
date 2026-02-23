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
        Schema::table('reports', function (Blueprint $table) {
            // Track submission to super admin by quarter
            $table->boolean('submitted_to_super_admin')->default(false)->after('revision_requested');
            $table->timestamp('submitted_to_super_admin_at')->nullable()->after('submitted_to_super_admin');
            $table->unsignedBigInteger('submitted_to_super_admin_by')->nullable()->after('submitted_to_super_admin_at');
            
            $table->foreign('submitted_to_super_admin_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'submitted_to_super_admin_by')) {
                $table->dropForeign(['submitted_to_super_admin_by']);
            }
            if (Schema::hasColumn('reports', 'submitted_to_super_admin')) {
                $table->dropColumn('submitted_to_super_admin');
            }
            if (Schema::hasColumn('reports', 'submitted_to_super_admin_at')) {
                $table->dropColumn('submitted_to_super_admin_at');
            }
            if (Schema::hasColumn('reports', 'submitted_to_super_admin_by')) {
                $table->dropColumn('submitted_to_super_admin_by');
            }
        });
    }
};
