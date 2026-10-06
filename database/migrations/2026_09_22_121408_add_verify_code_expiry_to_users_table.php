<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'verify_code_expires_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('verify_code_expires_at')->nullable()->after('verify_code_status');
            });
        }

        // Grandfather in every account that existed before email OTP verification was added: without this,
        // all of them have a null email_verified_at (nothing ever set it) and would be asked to verify an
        // email on their next login, which they never signed up expecting.
        DB::table('users')
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => DB::raw('created_at'), 'verify_code_status' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'verify_code_expires_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('verify_code_expires_at');
            });
        }
    }
};
