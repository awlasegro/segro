<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferenceCodes extends Model
{
    use HasFactory;
    protected $table = 'reference_codes';
    protected $fillable = [
        'code',
        'description',
        'used_by_user_id',
        'used_at',
        'status',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    public function usedBy()
    {
        return $this->belongsTo(User::class, 'used_by_user_id');
    }

    // "Available" means usable for a new registration right now: not
    // already consumed by someone, and not switched off by an admin.
    public function scopeAvailable($query)
    {
        return $query->whereNull('used_by_user_id')->where('status', 'active');
    }
}
