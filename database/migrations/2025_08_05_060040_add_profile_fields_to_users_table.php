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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'job_title')) {
                $table->string('job_title')->nullable()->after('department');
            }
            if (!Schema::hasColumn('users', 'location')) {
                $table->string('location')->nullable()->after('job_title');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('location');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('bio');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            
            if (Schema::hasColumn('users', 'phone')) {
                $columnsToDrop[] = 'phone';
            }
            if (Schema::hasColumn('users', 'department')) {
                $columnsToDrop[] = 'department';
            }
            if (Schema::hasColumn('users', 'job_title')) {
                $columnsToDrop[] = 'job_title';
            }
            if (Schema::hasColumn('users', 'location')) {
                $columnsToDrop[] = 'location';
            }
            if (Schema::hasColumn('users', 'bio')) {
                $columnsToDrop[] = 'bio';
            }
            if (Schema::hasColumn('users', 'avatar')) {
                $columnsToDrop[] = 'avatar';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
