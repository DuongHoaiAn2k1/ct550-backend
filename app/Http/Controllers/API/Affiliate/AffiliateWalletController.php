<?php

namespace App\Http\Controllers\API\Affiliate;

use Illuminate\Http\Request;
use App\Models\AffiliateWallet;
use App\Http\Controllers\Controller;

class AffiliateWalletController extends Controller
{
    public function increaseBalance(Request $request)
    {
        try {
            $affiliateWallet = AffiliateWallet::where('affiliate_user_id', $request->affiliate_user_id)->first();
            $affiliateWallet->balance = $request->balance;
            $affiliateWallet->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Tăng số dư thành công'
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function decreaseBalance(Request $request)
    {
        try {
            $affiliateWallet = AffiliateWallet::where('affiliate_user_id', $request->affiliate_user_id)->first();
            $affiliateWallet->balance = $affiliateWallet->balance - $request->balance;
            $affiliateWallet->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Giảm số dư thành công'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getBalance()
    {
        try {
            $user_id = auth()->user()->id;
            $affiliateWallet = AffiliateWallet::where('affiliate_user_id', $user_id)->first();
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy số dư thành công',
                'data' => $affiliateWallet->balance
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
