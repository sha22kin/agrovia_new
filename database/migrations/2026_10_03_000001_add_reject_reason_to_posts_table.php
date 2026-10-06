<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Why the admin rejected an ad; shown to the seller on My Ads and in the notification.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('posts', 'reject_reason')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->text('reject_reason')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('posts', 'reject_reason')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('reject_reason');
            });
        }
    }
};
