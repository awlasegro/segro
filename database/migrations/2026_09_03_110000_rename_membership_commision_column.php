<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('memberships', 'commision') && !Schema::hasColumn('memberships', 'commission')) {
            DB::statement('ALTER TABLE memberships CHANGE commision commission DECIMAL(11,2) NOT NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('memberships', 'commission') && !Schema::hasColumn('memberships', 'commision')) {
            DB::statement('ALTER TABLE memberships CHANGE commission commision DECIMAL(11,2) NOT NULL');
        }
    }
};
