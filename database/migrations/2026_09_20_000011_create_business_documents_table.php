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
        if (Schema::hasTable('business_documents')) {
            return;
        }

        // Papers a user uploads to get verified. The files are kept on the private disk, not in public/.
        Schema::create('business_documents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->index();
            $table->string('type', 40);
            $table->string('file_path', 255);
            $table->string('original_name', 191)->nullable();
            $table->string('status', 10)->default('pending')->comment('pending, verified, rejected');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_documents');
    }
};
