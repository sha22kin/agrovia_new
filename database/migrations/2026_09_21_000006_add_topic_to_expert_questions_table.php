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
        if (Schema::hasColumn('expert_questions', 'topic')) {
            return;
        }

        // What the question is about (Crop Disease, Irrigation, Soil Health ...). Experts see the questions of their specializations first.
        Schema::table('expert_questions', function (Blueprint $table) {
            $table->string('topic', 60)->nullable()->after('body');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('expert_questions', 'topic')) {
            Schema::table('expert_questions', function (Blueprint $table) {
                $table->dropColumn('topic');
            });
        }
    }
};
