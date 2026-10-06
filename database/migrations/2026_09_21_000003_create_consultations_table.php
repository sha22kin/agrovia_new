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
        if (Schema::hasTable('consultations')) {
            return;
        }

        // A session a farmer books with an expert.
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('expert_id')->index();
            $table->bigInteger('user_id')->index()->comment('the farmer');
            $table->string('topic', 191);
            $table->string('mode', 10)->default('video')->comment('video, phone, onsite');
            $table->timestamp('scheduled_at')->nullable()->index();
            $table->string('status', 10)->default('pending')->comment('pending, confirmed, completed, cancelled');
            $table->decimal('fee', 12, 2)->default(0);
            $table->string('meeting_link', 255)->nullable();
            $table->tinyInteger('rating')->nullable()->comment('1-5, given by the farmer after the session');
            $table->text('review')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
