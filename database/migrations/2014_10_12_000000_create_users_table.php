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
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('permission_id')->nullable();
            $table->string('name', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->text('profile')->nullable();
            $table->string('company_name', 200)->nullable();
            $table->text('address_line1')->nullable();
            $table->text('address_line2')->nullable();
            $table->string('postal_address', 250)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->integer('city')->nullable();
            $table->integer('district')->nullable();
            $table->integer('division')->nullable();
            $table->integer('country')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender', 10)->nullable();
            $table->boolean('status')->default(1)->comment('0=Inactive, 1=Active, 2=draft');
            $table->boolean('fetured')->default(0)->comment('0=no fetured, 1=Fetured');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('password_show', 191)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->string('reset_remember', 100)->nullable();
            $table->string('api_token', 100)->nullable();
            $table->string('device_key')->nullable();
            $table->string('verify_code', 100)->nullable();
            $table->boolean('verify_code_status')->default(0);
            $table->float('balance', 10, 2)->default(0);
            $table->boolean('subscriber')->default(0);
            $table->boolean('customer')->default(1);
            $table->boolean('business')->default(0);
            $table->boolean('admin')->default(0);
            $table->bigInteger('addedby_id')->nullable();
            $table->timestamp('addedby_at')->nullable();
            $table->timestamps();
            $table->unique('email');
            $table->unique('mobile', 'mobile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally empty: this table is owned by the dump and holds real users.
    }
};
