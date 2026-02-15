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
            // Increase personal_fte precision from decimal(3,1) to decimal(8,2)
            $table->decimal('personal_fte', 8, 2)->default(0.00)->change();
            
            // Increase income_euros and expenditure_euros precision from decimal(10,2) to decimal(15,2)
            $table->decimal('income_euros', 15, 2)->default(0.00)->change();
            $table->decimal('expenditure_euros', 15, 2)->default(0.00)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Revert personal_fte back to decimal(3,1)
            $table->decimal('personal_fte', 3, 1)->default(0.0)->change();
            
            // Revert income_euros and expenditure_euros back to decimal(10,2)
            $table->decimal('income_euros', 10, 2)->default(0.00)->change();
            $table->decimal('expenditure_euros', 10, 2)->default(0.00)->change();
        });
    }
};
