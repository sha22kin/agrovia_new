<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only the position changes: users.expert sits beside the other role flags (customer, business, admin).
        if (!Schema::hasColumn('users', 'expert') || !in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            return;
        }

        DB::statement('ALTER TABLE `users` MODIFY `expert` TINYINT(1) NOT NULL DEFAULT 0 AFTER `admin`');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('users', 'expert') || !in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            return;
        }

        DB::statement('ALTER TABLE `users` MODIFY `expert` TINYINT(1) NOT NULL DEFAULT 0 AFTER `updated_at`');
    }
};
