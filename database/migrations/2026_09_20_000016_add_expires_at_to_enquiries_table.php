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
        if (Schema::hasColumn('enquiries', 'expires_at')) {
            return;
        }

        // An open enquiry stops taking quotes after this date; the buyer can renew it.
        Schema::table('enquiries', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable();
        });

        // Enquiries posted before this column keep 30 days from the day they were posted.
        DB::table('enquiries')->whereNull('expires_at')->update(['expires_at' => DB::raw('DATE_ADD(created_at, INTERVAL 30 DAY)')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('enquiries', 'expires_at')) {
            Schema::table('enquiries', function (Blueprint $table) {
                $table->dropColumn('expires_at');
            });
        }
    }
};
