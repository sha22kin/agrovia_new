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
        if (Schema::hasTable('saved_searches')) {
            return;
        }

        // A search of the ads a buyer wants to run again.
        Schema::create('saved_searches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->index();
            $table->string('name', 120);
            $table->string('keyword', 120);
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('district_id')->nullable()->comment('countries.id (type 3)');
            $table->decimal('min_price', 12, 2)->nullable();
            $table->decimal('max_price', 12, 2)->nullable();
            $table->boolean('alerts')->default(true);
            $table->timestamp('checked_at')->nullable()->comment('ads posted after this are "new"');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_searches');
    }
};
