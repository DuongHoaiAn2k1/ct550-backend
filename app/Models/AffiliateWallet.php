<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateWallet extends Model
{
    use HasFactory;

    protected $table = 'affiliate_wallets';

    protected $primaryKey = 'wallet_id';

    protected $fillable = [
        'affiliate_user_id',
        'balance',
    ];

    public function affiliateUser()
    {
        return $this->belongsTo(User::class, 'affiliate_user_id');
    }
}
