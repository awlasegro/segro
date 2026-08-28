<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reference_codes', function (Blueprint $table) {
            $table->unsignedBigInteger('used_by_user_id')->nullable()->after('description');
            $table->timestamp('used_at')->nullable()->after('used_by_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reference_codes', function (Blueprint $table) {
            $table->dropColumn(['used_by_user_id', 'used_at']);
        });
    }
};
