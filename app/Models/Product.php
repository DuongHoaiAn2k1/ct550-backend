<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $primaryKey = 'product_id';
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

    public function order_detail()
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }

    public function favorite()
    {
        return $this->belongsToMany(Favorite::class, 'favorite_id');
    }
    public function review()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function batches()
    {
        return $this->hasMany(Batch::class, 'product_id', 'product_id');
    }

    public function product_promotion()
    {
        return $this->hasMany(ProductPromotion::class, 'product_id');
    }

    public function commission()
    {
        return $this->hasOne(Commission::class, 'product_id', 'product_id');
    }

    public function affiliate()
    {
        return $this->hasOne(AffiliateLink::class, 'product_id', 'product_id');
    }
}
