<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Updated by TrackUserLocation middleware whenever a logged-in
            // user's IP changes — used to show admins an approximate
            // "current location" (city-level, via IP geolocation) in the
            // support chat panel.
            $table->string('last_ip_address')->nullable();
            $table->string('last_location')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_ip_address', 'last_location']);
        });
    }
};
