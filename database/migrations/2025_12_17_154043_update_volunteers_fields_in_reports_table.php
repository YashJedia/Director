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
            $table->integer('volunteers_chatters')->default(0)->after('volunteers_achieved_q1');
            $table->integer('volunteers_mentors')->default(0)->after('volunteers_chatters');
            $table->integer('volunteers_content_creators')->default(0)->after('volunteers_mentors');
            $table->integer('volunteers_others')->default(0)->after('volunteers_content_creators');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['volunteers_chatters', 'volunteers_mentors', 'volunteers_content_creators', 'volunteers_others']);
        });
    }
};
