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
        if (Schema::hasTable('posts')) {
            return;
        }

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->nullable();
            $table->string('slug', 250)->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->float('price', 10, 2)->default(0);
            $table->boolean('negotiable')->default(0);
            $table->integer('category_id')->nullable();
            $table->integer('sub_category_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('thana_id')->nullable();
            $table->text('address_line')->nullable();
            $table->string('phone_number', 20)->default('');
            $table->boolean('hide_phone')->default(0);
            $table->unsignedBigInteger('view')->default(0);
            $table->integer('type')->default(0)->comment('0=Page,1=Post, 3=Service');
            $table->text('tags')->nullable();
            $table->string('template', 100)->nullable();
            $table->string('seo_title', 191)->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keyword')->nullable();
            $table->text('search_key')->nullable();
            $table->string('status', 10)->default('temp')->comment('temp,active,inactive');
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
        Schema::dropIfExists('posts');
    }
};
