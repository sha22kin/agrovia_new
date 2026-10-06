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
        if (Schema::hasTable('community_posts')) {
            return;
        }

        // A discussion started by a buyer or a seller.
        Schema::create('community_posts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->index();
            $table->string('tag', 30)->comment('Market Price, Farming Tips, Discussion, Question');
            $table->string('title', 191);
            $table->text('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_posts');
    }
};
