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
        if (Schema::hasTable('enquiry_quotes')) {
            return;
        }

        // A seller's answer to an enquiry.
        Schema::create('enquiry_quotes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('enquiry_id')->index();
            $table->bigInteger('user_id')->nullable()->comment('the seller');
            $table->decimal('price', 12, 2)->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiry_quotes');
    }
};
