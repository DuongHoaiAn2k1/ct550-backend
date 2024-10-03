<?php

namespace App\Http\Controllers\API\Affiliate;

use App\Models\Commission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommissionController extends Controller
{

    public function index()
    {
        try {
            $commissions = Commission::with('product')->with('product.affiliate')->with('product.affiliate.affiliateUser')->get();

            return response([
                'status' => 'success',
                'data' => $commissions
            ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $checkExist = Commission::where('product_id', $request->product_id)->first();
            if ($checkExist) {
                return response([
                    'status' => 'error',
                    'message' => 'Sản phẩm đã tồn tại'
                ], 400);
            }

            $commission = new Commission();
            $commission->product_id = $request->product_id;
            $commission->commission_rate = $request->commission_rate;
            $commission->save();
            return response([
                'status' => 'success',
                'data' => $commission
            ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $commission_id)
    {
        try {
            $commission = Commission::where('commission_id', $commission_id)->first();
            $commission->commission_rate = $request->commission_rate;
            $commission->save();
            return response([
                'status' => 'success',
                'data' => $commission
            ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($commission_id)
    {
        try {
            $commission = Commission::where('commission_id', $commission_id)->first();
            $commission->delete();
            return response([
                'status' => 'success',
                'data' => $commission
            ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
