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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('amenity')->nullable();
            $table->text('displayName')->nullable();
            $table->decimal('lat', 11, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->string('shop')->nullable();
            $table->string('building')->nullable();
            $table->string('house_number')->nullable();
            $table->string('landuse')->nullable();
            $table->string('aeroway')->nullable();
            $table->string('railway')->nullable();
            $table->string('road')->nullable();
            $table->string('municipality')->nullable();
            $table->string('neighbourhood')->nullable();
            $table->string('city_district')->nullable();
            $table->string('city')->nullable();
            $table->string('hamlet')->nullable();
            $table->string('village')->nullable();
            $table->string('town')->nullable();
            $table->string('county')->nullable();
            $table->string('suburb')->nullable();
            $table->string('state')->nullable();
            $table->string('state_district')->nullable();
            $table->string('ISO3166-2-lvl4')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country');
            $table->string('country_code');

            $table->morphs('addressable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
