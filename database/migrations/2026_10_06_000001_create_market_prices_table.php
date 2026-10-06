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
        if (Schema::hasTable('market_prices')) {
            return;
        }

        // Daily market price of a crop, published by the admin. One row per item per day.
        Schema::create('market_prices', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('name_bn', 60)->nullable();
            $table->string('icon', 16)->nullable()->comment('emoji shown beside the name');
            $table->string('unit', 20)->default('kg');
            $table->decimal('price', 10, 2);
            $table->date('price_date');
            $table->bigInteger('addedby_id')->nullable();
            $table->timestamps();
            $table->unique(['name', 'price_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_prices');
    }
};
