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
        if (Schema::hasTable('payment_methods')) {
            return;
        }

        // Mobile banking accounts and bank accounts a user pays with / is paid to. No card numbers, PINs or CVV are stored.
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->index();
            $table->string('type', 20)->comment('bKash, Nagad, Rocket, Bank Transfer');
            $table->string('account_number', 40);
            $table->string('account_kind', 20)->nullable()->comment('mobile banking: Personal, Agent, Merchant');
            $table->string('bank_name', 80)->nullable();
            $table->string('branch', 80)->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
