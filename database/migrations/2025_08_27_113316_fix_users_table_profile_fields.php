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
        // This migration is now redundant since add_profile_fields_to_users_table
        // handles all the necessary columns. No action needed.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is now redundant since add_profile_fields_to_users_table
        // handles all the necessary columns. No action needed.
    }
};
