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
        if (Schema::hasTable('permissions')) {
            return;
        }

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->json('permission')->nullable();
            $table->string('status', 10)->nullable()->comment('active,inactive,temp');
            $table->integer('addedby_id')->nullable();
            $table->integer('editedby_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
