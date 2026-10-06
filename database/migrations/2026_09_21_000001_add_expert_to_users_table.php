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
        if (Schema::hasColumn('users', 'expert')) {
            return;
        }

        // An expert has the expert dashboard; the admin sets the flag (users edit page).
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('expert')->default(false)->after('admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'expert')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('expert');
            });
        }
    }
};
