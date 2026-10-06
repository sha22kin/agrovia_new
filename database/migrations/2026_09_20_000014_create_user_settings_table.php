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
        if (!Schema::hasTable('user_settings')) {
            // Notification, privacy and language / currency choices of a user.
            Schema::create('user_settings', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('user_id')->unique();
                $table->boolean('notify_orders')->default(true);
                $table->boolean('notify_enquiries')->default(true);
                $table->boolean('notify_price_drop')->default(false);
                $table->boolean('notify_sms')->default(true);
                $table->boolean('notify_marketing')->default(false);
                $table->boolean('profile_visible')->default(true);
                $table->boolean('show_contact')->default(false);
                $table->string('language', 5)->default('en');
                $table->string('currency', 5)->default('BDT');
                $table->timestamps();
            });
        }

        // Set when a user deactivates the account; logging in again reactivates it.
        if (!Schema::hasColumn('users', 'deactivated_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('deactivated_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');

        if (Schema::hasColumn('users', 'deactivated_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('deactivated_at');
            });
        }
    }
};
