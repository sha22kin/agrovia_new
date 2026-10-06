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
        if (Schema::hasTable('community_comments')) {
            return;
        }

        Schema::create('community_comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('post_id')->index();
            $table->bigInteger('user_id');
            $table->text('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_comments');
    }
};
