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
        // Add CHECK constraint to ensure only valid roles
        // This works for PostgreSQL, MySQL 8.0.16+, SQLite
        Schema::table('users', function (Blueprint $table) {
            // For databases that support CHECK constraint
            if (DB::getDriverName() !== 'sqlite') {
                DB::statement(
                    "ALTER TABLE users ADD CONSTRAINT check_valid_role 
                    CHECK (role IN ('super_admin', 'admin_school'))"
                );
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                DB::statement('ALTER TABLE users DROP CONSTRAINT check_valid_role');
            }
        });
    }
};
