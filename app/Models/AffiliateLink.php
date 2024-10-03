<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateLink extends Model
{
    use HasFactory;
    protected $primaryKey = 'affiliate_link_id';

    protected $table = 'affiliate_links';

    protected $fillable = [
        'affiliate_link',
        'affiliate_user_id',
        'product_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function affiliateUser()
    {
        return $this->belongsTo(User::class, 'affiliate_user_id');
    }
}
