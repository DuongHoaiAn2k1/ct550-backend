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

    public function order_detail()
    {
        return $this->belongsToMany(OrderDetail::class, 'order_detail_id');
    }

    public function favorite()
    {
        return $this->belongsToMany(Favorite::class, 'favorite_id');
    }
    public function review()
    {
        return $this->hasMany(Review::class, 'product_id');
    }
}
