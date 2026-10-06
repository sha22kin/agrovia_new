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
        if (Schema::hasTable('post_extras')) {
            return;
        }

        Schema::create('post_extras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('src_id')->nullable();
            $table->string('name', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('content')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->integer('drag')->default(0);
            $table->string('status', 10)->nullable();
            $table->integer('type')->default(0)->comment('0=Page,1=Subscribe');
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
        Schema::dropIfExists('post_extras');
    }
};
