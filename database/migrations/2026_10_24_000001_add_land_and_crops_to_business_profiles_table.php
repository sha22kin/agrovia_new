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
        if (Schema::hasColumn('business_profiles', 'land_area')) {
            return;
        }

        // Seller's farm: total land and the main crops or products.
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->decimal('land_area', 10, 2)->nullable()->after('areas');
            $table->string('land_unit', 10)->nullable()->after('land_area')->comment('Bigha, Acre');
            $table->string('main_crops', 300)->nullable()->after('land_unit')->comment('comma separated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->dropColumn(['land_area', 'land_unit', 'main_crops']);
        });
    }
};
