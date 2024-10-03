<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateWithdrawal extends Model
{
    use HasFactory;
    protected $table = 'affiliate_withdrawals';
    protected $primaryKey = 'withdrawal_id';

    protected $fillable = [
        'affiliate_user_id',
        'amount',
        'status',
        'bank_name',
        'account_number',
        'account_holder_name',
    ];

    public function affiliateUser()
    {
        return $this->belongsTo(User::class, 'affiliate_user_id');
    }
}
