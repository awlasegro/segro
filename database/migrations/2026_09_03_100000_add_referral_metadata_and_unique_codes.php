<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unique('reference_code', 'users_reference_code_unique');
            $table->index('parent_id', 'users_parent_id_index');
        });

        Schema::table('funds', function (Blueprint $table) {
            if (!Schema::hasColumn('funds', 'commission_type')) {
                $table->string('commission_type')->nullable()->after('type');
            }
            if (!Schema::hasColumn('funds', 'source_user_id')) {
                $table->unsignedBigInteger('source_user_id')->nullable();
            }
            if (!Schema::hasColumn('funds', 'referrer_user_id')) {
                $table->unsignedBigInteger('referrer_user_id')->nullable();
            }
            if (!Schema::hasColumn('funds', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable();
            }
            if (!Schema::hasColumn('funds', 'description')) {
                $table->text('description')->nullable();
            }
            $table->unique(
                ['order_id', 'referrer_user_id', 'commission_type'],
                'funds_referral_payout_unique'
            );
            $table->index(['referrer_user_id', 'commission_type'], 'funds_referral_reporting_index');
        });
    }

    public function down(): void
    {
        Schema::table('funds', function (Blueprint $table) {
            $table->dropUnique('funds_referral_payout_unique');
            $table->dropIndex('funds_referral_reporting_index');
            $table->dropColumn([
                'commission_type',
                'source_user_id',
                'referrer_user_id',
                'order_id',
                'description',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_reference_code_unique');
            $table->dropIndex('users_parent_id_index');
        });
    }
};
