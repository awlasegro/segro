<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->nullable();
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->unique()->nullable();
            }
            if (!Schema::hasColumn('users', 'vallet_password')) {
                $table->string('vallet_password')->nullable();
            }
            if (!Schema::hasColumn('users', 'reference_code')) {
                $table->string('reference_code')->nullable();
            }
            if (!Schema::hasColumn('users', 'membership_level_id')) {
                $table->unsignedBigInteger('membership_level_id')->default(1);
            }
            if (!Schema::hasColumn('users', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->default(0);
            }
            if (!Schema::hasColumn('users', 'credibility')) {
                $table->decimal('credibility', 8, 2)->default(100);
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active');
            }
            if (!Schema::hasColumn('users', 'wallet_status')) {
                $table->string('wallet_status')->default('active');
            }
            if (!Schema::hasColumn('users', 'funds')) {
                $table->decimal('funds', 12, 2)->default(0);
            }
            if (!Schema::hasColumn('users', 'user_type')) {
                $table->unsignedTinyInteger('user_type')->default(0);
            }
            if (!Schema::hasColumn('users', 'min_withdraw')) {
                $table->decimal('min_withdraw', 12, 2)->default(50);
            }
            if (!Schema::hasColumn('users', 'max_withdraw')) {
                $table->decimal('max_withdraw', 12, 2)->default(500);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'phone',
                'vallet_password',
                'reference_code',
                'membership_level_id',
                'parent_id',
                'credibility',
                'status',
                'wallet_status',
                'funds',
                'user_type',
                'min_withdraw',
                'max_withdraw',
            ]);
        });
    }
};
