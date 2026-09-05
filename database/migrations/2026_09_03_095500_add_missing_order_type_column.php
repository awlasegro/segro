<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('orders', 'type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('type')->nullable()->after('order_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
