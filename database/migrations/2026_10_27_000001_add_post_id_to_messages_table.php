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
        if (Schema::hasColumn('messages', 'post_id')) {
            return;
        }

        Schema::table('messages', function (Blueprint $table) {
            $table->bigInteger('post_id')->nullable()->index()->after('sender_id')->comment('the ad the message is about');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('post_id');
        });
    }
};
