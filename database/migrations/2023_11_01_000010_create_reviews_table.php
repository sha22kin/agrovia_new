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
        // The table may already exist when the database was restored from nit_agroviea.sql.
        if (Schema::hasTable('reviews')) {
            return;
        }

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('src_id')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->string('name', 200)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('title', 200)->nullable();
            $table->string('website', 200)->nullable();
            $table->text('content')->nullable();
            $table->integer('rating')->default(0);
            $table->integer('type')->default(0)->comment('0=review,1=Comments');
            $table->string('status', 10)->default('temp')->comment('temp, active, inactive');
            $table->boolean('fetured')->default(0);
            $table->bigInteger('addedby_id')->nullable();
            $table->bigInteger('editedby_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
