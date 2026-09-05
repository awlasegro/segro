<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'selected_order_commission_rate',
    ];

    /**
     * The singleton settings row, created with the default rate the first
     * time it's needed (same lazy-create pattern as PlatformWallet).
     *
     * @return \App\Models\OrderSetting
     */
    public static function current()
    {
        return static::first() ?? static::create([
            'selected_order_commission_rate' => 10.00,
        ]);
    }
}
