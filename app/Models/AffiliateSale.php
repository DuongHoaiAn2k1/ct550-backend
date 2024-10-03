<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateSale extends Model
{
    use HasFactory;

    protected $primaryKey = 'affiliate_sale_id';

    protected $table = 'affiliate_sales';

    protected $fillable = [
        'affiliate_user_id',
        'product_id',
        'order_id',
        'commission_amount',
        'commission_rate',
        'order_status'
    ];

    public function affiliateUser()
    {
        return $this->belongsTo(User::class, 'affiliate_user_id');
    }

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Relationship with Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
