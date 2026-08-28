<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * An image-only message has no text, so `message` can no longer be
     * required. Using raw SQL here instead of Schema::table(...)->change()
     * since doctrine/dbal (needed for column modification) isn't installed.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE chat_messages MODIFY message TEXT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE chat_messages MODIFY message TEXT NOT NULL');
    }
};
