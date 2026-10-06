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
        if (Schema::hasTable('enquiries')) {
            return;
        }

        // A buyer's requirement ("Looking for quality paddy, 8000 kg") that sellers answer with quotes.
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable()->index();
            $table->bigInteger('category_id')->nullable();
            $table->string('title', 191);
            $table->text('description')->nullable();
            $table->decimal('quantity', 12, 2)->nullable();
            $table->string('unit', 20)->nullable();
            $table->string('status', 10)->default('open')->comment('open, closed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
