<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;
    protected $table = 'batches';
    protected $primaryKey = 'batch_id';

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }


    public function orderDetailBatch()
    {
        return $this->hasMany(OrderDetailBatch::class, 'batch_id');
    }
}
