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
        Schema::table('attributes', function (Blueprint $table) {
            if (!Schema::hasColumn('attributes', 'name_bn')) {
                $table->string('name_bn', 191)->nullable()->after('name');
            }
            // Position of a featured category in the home page "Marketplace categories" grid.
            if (!Schema::hasColumn('attributes', 'drag')) {
                $table->integer('drag')->nullable()->after('fetured');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropColumn(['name_bn', 'drag']);
        });
    }
};
