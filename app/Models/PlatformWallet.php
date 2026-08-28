<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformWallet extends Model
{
    use HasFactory;

    protected $table = 'platform_wallets';

    protected $fillable = [
        'wallet_type',
        'wallet_address',
        'qr_code',
        'created_at',
        'updated_at',
    ];
}
