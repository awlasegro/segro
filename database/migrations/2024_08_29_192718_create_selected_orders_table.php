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
        Schema::create('selected_orders', function (Blueprint $table) {
            Schema::create('selected_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id');
                $table->foreignId('order_list_id');
                $table->integer('after_order_number');
                $table->timestamps();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('selected_orders');
    }
};
