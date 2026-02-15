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
            $table->boolean('two_factor_enabled')->default(false)->after('avatar');
            $table->string('two_factor_secret')->nullable()->after('two_factor_enabled');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->boolean('is_invited')->default(false)->after('two_factor_recovery_codes');
            $table->timestamp('invited_at')->nullable()->after('is_invited');
            $table->timestamp('password_set_at')->nullable()->after('invited_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_enabled',
                'two_factor_secret', 
                'two_factor_recovery_codes',
                'is_invited',
                'invited_at',
                'password_set_at'
            ]);
        });
    }
};