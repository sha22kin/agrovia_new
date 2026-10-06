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
        if (Schema::hasTable('expert_profiles')) {
            return;
        }

        // What the expert dashboard shows about an expert. verified_at is set by the admin.
        Schema::create('expert_profiles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unique();
            $table->string('headline', 150)->nullable()->comment('e.g. Agronomist · Soil & Crop Health Specialist');
            $table->string('specializations', 300)->nullable()->comment('comma separated');
            $table->decimal('fee', 12, 2)->nullable()->comment('consultation fee');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expert_profiles');
    }
};
