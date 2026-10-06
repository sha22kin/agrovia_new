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
        if (!Schema::hasTable('expert_questions')) {
            // A question a farmer asks the experts (Ask an Expert).
            Schema::create('expert_questions', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('user_id')->index()->comment('the asker');
                $table->string('title', 191);
                $table->text('body')->nullable();
                $table->string('status', 10)->default('open')->comment('open, closed');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('expert_answers')) {
            Schema::create('expert_answers', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('question_id')->index();
                $table->bigInteger('expert_id')->index();
                $table->text('body');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expert_answers');
        Schema::dropIfExists('expert_questions');
    }
};
