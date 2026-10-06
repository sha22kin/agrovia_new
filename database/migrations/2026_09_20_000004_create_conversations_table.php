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
        if (Schema::hasTable('conversations')) {
            return;
        }

        // One chat between a buyer and a seller. The buyer starts it from an ad.
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('buyer_id')->index();
            $table->bigInteger('seller_id')->index();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            $table->unique(['buyer_id', 'seller_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
