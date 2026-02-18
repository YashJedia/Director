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
            // Add field to track if revision is requested
            $table->boolean('revision_requested')->default(false)->after('review_status');
            // Add timestamp for when revision was requested
            $table->timestamp('revision_requested_at')->nullable()->after('revision_requested');
            // Add revision request reason/comments
            $table->text('revision_reason')->nullable()->after('revision_requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'revision_requested')) {
                $table->dropColumn('revision_requested');
            }
            if (Schema::hasColumn('reports', 'revision_requested_at')) {
                $table->dropColumn('revision_requested_at');
            }
            if (Schema::hasColumn('reports', 'revision_reason')) {
                $table->dropColumn('revision_reason');
            }
        });
    }
};
