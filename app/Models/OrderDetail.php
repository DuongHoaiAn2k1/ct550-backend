<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = 'order_detail';
    protected $primaryKey = 'order_detail_id';

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function orderDetailBatch()
    {
        return $this->hasMany(OrderDetailBatch::class, 'order_detail_id');
    }
}
