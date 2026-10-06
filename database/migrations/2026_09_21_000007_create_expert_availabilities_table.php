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
        if (!Schema::hasTable('expert_availabilities')) {
            // The hours of a week day an expert can be booked (farmers pick a consultation inside these hours).
            Schema::create('expert_availabilities', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('expert_id')->index();
                $table->tinyInteger('day_of_week')->comment('0 = Sunday ... 6 = Saturday');
                $table->time('start_time');
                $table->time('end_time');
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('expert_profiles', 'session_minutes')) {
            Schema::table('expert_profiles', function (Blueprint $table) {
                $table->smallInteger('session_minutes')->default(30)->after('fee')->comment('length of one consultation');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expert_availabilities');

        if (Schema::hasColumn('expert_profiles', 'session_minutes')) {
            Schema::table('expert_profiles', function (Blueprint $table) {
                $table->dropColumn('session_minutes');
            });
        }
    }
};
