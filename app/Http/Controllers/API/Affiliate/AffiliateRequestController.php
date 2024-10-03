<?php

namespace App\Http\Controllers\API\Affiliate;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AffiliateWallet;
use App\Models\AffiliateRequest;
use App\Http\Controllers\Controller;
use App\Events\Affiliate\AffiliateApproved;
use App\Events\Affiliate\AffiliateCreateRequest;

class AffiliateRequestController extends Controller
{


    public function getAll()
    {
        try {
            $listRequest = AffiliateRequest::with('user')->get();
            // \Logger::info($listRequest);
            return response([
                'status' => 'success',
                'data' => $listRequest

            ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function create()
    {
        try {
            $user_id = auth()->user()->id;
            $affiliateRequest = new AffiliateRequest();
            $affiliateRequest->user_id = $user_id;
            $affiliateRequest->save();
            event(new AffiliateCreateRequest());
            return response([
                'status' => 'success',
                'data' => $affiliateRequest

            ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function approved($affiliate_request_id)
    {
        try {
            $affiliateRequest = AffiliateRequest::where('affiliate_request_id', $affiliate_request_id)->first();

            if (!$affiliateRequest) {
                return response([
                    'status' => 'error',
                    'message' => 'Affiliate request not found'
                ], 404);
            }

            $affiliateRequest->status = 'approved';
            $affiliateRequest->save();

            $user = User::where('id', $affiliateRequest->user_id)->first();
            $user->assignRole('affiliate_marketer');
            $user->save();

            $affiliateWallet = new AffiliateWallet();
            $affiliateWallet->affiliate_user_id = $affiliateRequest->user_id;
            $affiliateWallet->balance = 0;
            $affiliateWallet->save();
            event(new AffiliateApproved());
            return response([
                'status' => 'success',
                'data' => $affiliateRequest,
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function reject($affiliate_request_id)
    {
        try {
            $affiliateRequest = AffiliateRequest::where('affiliate_request_id', $affiliate_request_id)->first();
            $affiliateRequest->status = 'rejected';
            $affiliateRequest->save();
            return response([
                'status' => 'success',
                'data' => $affiliateRequest

            ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkStatusUser()
    {
        try {
            $user_id = auth()->user()->id;
            $affiliateRequest = AffiliateRequest::where('user_id', $user_id)->first();
            $status = $affiliateRequest->status;
            return response()->json([
                'status' => 'success',
                'data' => $status
            ],  200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
