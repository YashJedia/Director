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
            // Add new fields to Organization section
            $table->decimal('income_from_fundraising_euros', 15, 2)->default(0.00)->after('income_euros');
            $table->integer('number_of_supporters')->default(0)->after('income_from_fundraising_euros');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['income_from_fundraising_euros', 'number_of_supporters']);
        });
    }
};
