<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'type',
        'status',
        'price',
        'commission',
        'total_amount',
        'source',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orderList()
{
    return $this->belongsTo(OrderList::class, 'order_id'); // Assuming 'order_id' is the foreign key in OrderList
}

}
