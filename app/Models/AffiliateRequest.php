<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateRequest extends Model
{
    use HasFactory;

    protected $table = 'affiliate_requests';
    protected $primaryKey = 'affiliate_request_id';

    protected $fillable = ['user_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
