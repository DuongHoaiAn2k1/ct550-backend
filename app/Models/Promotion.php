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
        'status',
    ];

    public function product_promotion()
    {
        return $this->hasMany(ProductPromotion::class, 'promotion_id');
    }

    public function batch_promotion()
    {
        return $this->hasMany(BatchPromotion::class, 'promotion_id');
    }
}
