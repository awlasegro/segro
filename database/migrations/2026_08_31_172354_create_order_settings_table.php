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
        Schema::create('order_settings', function (Blueprint $table) {
            $table->id();
            // Singleton settings row (same pattern as platform_wallets). Rate
            // is a whole-number percentage (e.g. 10.00 = 10%), applied to
            // orders matched to an admin-curated SelectedOrder slot.
            $table->decimal('selected_order_commission_rate', 5, 2)->default(10.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_settings');
    }
};
