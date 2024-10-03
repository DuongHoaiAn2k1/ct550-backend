<?php

namespace App\Http\Controllers\API\Affiliate;

use App\Models\Product;
use App\Models\Commission;
use Illuminate\Http\Request;
use App\Models\AffiliateSale;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AffiliateSaleController extends Controller
{
    // List all affiliate sales with pagination
    public function index()
    {
        $affiliateSales = AffiliateSale::with(['affiliateUser', 'product', 'order'])->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => $affiliateSales
        ]);
    }

    // Create a new affiliate sale
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'affiliate_user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'order_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 422);
        }

        try {
            $affiliateSale = new AffiliateSale();
            $commission = Commission::where('product_id', $request->product_id)->first();
            $product = Product::where('product_id', $request->product_id)->first();
            $affiliateSale->affiliate_user_id = $request->affiliate_user_id;
            $affiliateSale->product_id = $request->product_id;
            $affiliateSale->order_id = $request->order_id;
            $affiliateSale->commission_amount = round(($request->quantity * $product->product_price) * ($commission->commission_rate / 100));
            $affiliateSale->commission_rate = $commission->commission_rate;
            $affiliateSale->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Tạo hoa hồng thành công'
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
            $affiliateSales = AffiliateSale::with(['affiliateUser', 'product', 'order', 'order.orderDetail'])->where('affiliate_user_id', auth()->user()->id)->where('order_status', 'done')->get();
            return response()->json([
                'status' => 'success',
                'data' => $affiliateSales
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function changOrderStatus($order_id)
    {
        try {
            $affiliateSale = AffiliateSale::where('order_id', request()->order_id)->first();
            $affiliateSale->order_status = 'done';
            $affiliateSale->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật trạng thái thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
