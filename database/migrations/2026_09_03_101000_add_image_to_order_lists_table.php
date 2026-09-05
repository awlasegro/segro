<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('order_lists', 'image')) {
            Schema::table('order_lists', function (Blueprint $table) {
                $table->string('image')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('order_lists', 'image')) {
            Schema::table('order_lists', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};
