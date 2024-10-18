<?php

namespace App\Http\Controllers\API\Affiliate;

use App\Events\Affiliate\AffiliateWithdrawalEvent;
use App\Events\Affiliate\AffiliateWithdrawalSent;
use Illuminate\Http\Request;
use App\Models\AffiliateWallet;
use App\Models\AffiliateWithdrawal;
use App\Http\Controllers\Controller;

class AffiliateWithdrawalController extends Controller
{

    public function create(Request $request)
    {

        try {
            $affiliate_user_id = auth()->user()->id;
            $affiliaateWithdrawal = new AffiliateWithdrawal();
            $affiliaateWithdrawal->affiliate_user_id = $affiliate_user_id;
            $affiliaateWithdrawal->amount = $request->amount;
            $affiliaateWithdrawal->bank_name = $request->bank_name;
            $affiliaateWithdrawal->account_number = $request->account_number;
            $affiliaateWithdrawal->account_holder_name = $request->account_holder_name;
            $affiliaateWithdrawal->save();
            event(new AffiliateWithdrawalEvent());
            return response()->json([
                'status' => 'success',
                'message' => 'Tạo yêu cầu rút tiền thành công'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getByUser()
    {
        try {
            $user_id = auth()->user()->id;
            $affiliaateWithdrawal = AffiliateWithdrawal::where('affiliate_user_id', $user_id)->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy yêu cầu rút tiền thành công',
                'data' => $affiliaateWithdrawal
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAll()
    {
        try {
            $affiliaateWithdrawal = AffiliateWithdrawal::with('affiliateUser')->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy yêu cầu rút tiền thành công',
                'data' => $affiliaateWithdrawal
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function done($id)
    {
        try {
            $affiliaateWithdrawal = AffiliateWithdrawal::where('withdrawal_id', $id)->first();
            $affiliaateWithdrawal->status = 'done';
            $affiliaateWithdrawal->save();

            $affiliateWallet = AffiliateWallet::where('affiliate_user_id', $affiliaateWithdrawal->affiliate_user_id)->first();
            $affiliateWallet->balance -= $affiliaateWithdrawal->amount;
            $affiliateWallet->save();
            event(new AffiliateWithdrawalSent());
            return response()->json([
                'status' => 'success',
                'message' => 'Yêu cầu rút tiền đã được xử lý'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $affiliaateWithdrawal = AffiliateWithdrawal::where('withdrawal_id', $id)->first();
            if ($affiliaateWithdrawal->status == 'done') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Yêu cầu rút tiền không thành công'
                ], 500);
            }
            $affiliaateWithdrawal->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Xóa yêu cầu rút tiền thành công'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
