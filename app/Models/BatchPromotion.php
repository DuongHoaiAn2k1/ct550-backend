<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchPromotion extends Model
{
    use HasFactory;

    protected $primaryKey = 'batch_promotion_id';
    protected $table = 'batch_promotions';

    protected $fillable = [
        'promotion_id',
        'batch_id',
        'discount_price',
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
    
}
