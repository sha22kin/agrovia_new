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
        if (Schema::hasTable('addresses')) {
            return;
        }

        // Delivery and billing addresses a user keeps; one of them is the default.
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->index();
            $table->string('label', 60);
            $table->string('address', 300);
            $table->string('phone', 30)->nullable();
            $table->bigInteger('district_id')->nullable()->comment('countries.id (type 3)');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
