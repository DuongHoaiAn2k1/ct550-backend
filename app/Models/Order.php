<?php

namespace App\Models;

use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function  user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
