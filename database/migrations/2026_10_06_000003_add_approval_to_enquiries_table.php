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
        if (Schema::hasColumn('enquiries', 'approval')) {
            return;
        }

        // A buyer's enquiry is shown to sellers (RFQ page, Buyer Requirements) only after the admin approves it.
        Schema::table('enquiries', function (Blueprint $table) {
            $table->string('approval', 10)->default('pending')->after('status')->comment('pending, approved, rejected');
            $table->text('reject_reason')->nullable()->after('approval');
        });

        // Enquiries posted before the review step stay visible.
        DB::table('enquiries')->update(['approval' => 'approved']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn(['approval', 'reject_reason']);
        });
    }
};
