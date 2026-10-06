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
        // The table may already exist when the database was restored from nit_agroviea.sql.
        if (Schema::hasTable('social_identities')) {
            return;
        }

        Schema::create('social_identities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('provider_name')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('provider_token')->nullable();
            $table->string('provider_img_url')->nullable();
            $table->timestamps();
            $table->unique('provider_id');
            $table->unique('provider_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_identities');
    }
};
