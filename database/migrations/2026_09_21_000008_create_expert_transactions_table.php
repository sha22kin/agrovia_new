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
        if (Schema::hasTable('expert_transactions')) {
            return;
        }

        // The money of an expert: consultation income (+), platform fees (-) and withdrawals (-).
        // The balance is the sum of the amounts, except withdrawals that were rejected or cancelled.
        Schema::create('expert_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('expert_id')->index();
            $table->string('type', 15)->comment('consultation, fee, withdrawal, adjustment');
            $table->decimal('amount', 12, 2)->comment('positive = income, negative = money out');
            $table->string('description', 191)->nullable();
            $table->bigInteger('consultation_id')->nullable()->index();
            $table->string('status', 10)->default('completed')->comment('completed, pending, paid, rejected, cancelled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expert_transactions');
    }
};
