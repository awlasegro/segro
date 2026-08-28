<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectedOrder extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'order_list_id', 'order_after'];

    public function orderList()
    {
        return $this->belongsTo(OrderList::class, 'order_list_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
