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
        if (Schema::hasTable('business_profiles')) {
            return;
        }

        // Business details of a buyer or seller. The name and description stay in users.company_name / users.profile.
        Schema::create('business_profiles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unique();
            $table->string('business_type', 30)->nullable();
            $table->string('license_no', 60)->nullable()->comment('trade license');
            $table->string('tin_bin', 60)->nullable();
            $table->smallInteger('established_year')->nullable();
            $table->string('employees', 20)->nullable();
            $table->string('address', 191)->nullable();
            $table->string('areas', 300)->nullable()->comment('comma separated areas of operation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_profiles');
    }
};
