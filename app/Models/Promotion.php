<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $primaryKey = 'promotion_id';

    protected $fillable = [
        'promotion_name',
        'discount_percentage',
        'user_group',
        'start_date',
        'end_date',
    ];

    public function product_promotion()
    {
        return $this->hasMany(ProductPromotion::class, 'promotion_id');
    }
}
