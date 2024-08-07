<?php

namespace App\Http\Controllers\API\Order;

use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrderDetailController extends Controller
{
    public function get($order_id)
    {
        try {
            $order = OrderDetail::where('order_id', $order_id)->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Get order detail successfully',
                'data' => $order
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $customMessage = [
                'quantity.required' => 'Số lượng không được để trống.',
                'order_id' => 'Mã đơn hàng không được để trống.',
                'product_id' => 'Id sản phẩm không được để trống.',
                'total_cost_detail' => 'Tổng tiền không được để trống.'

            ];

            $validate = Validator::make($request->all(), [
                'quantity' => 'required',
                'product_id' => 'required',
                'order_id' => 'required',
                'total_cost_detail' => 'required'
            ], $customMessage);

            if ($validate->fails()) {
                $errors = $validate->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'error' => $errors
                ], 422);
            } else {
                $orderDetail = new OrderDetail();
                $orderDetail->quantity = $request->quantity;
                $orderDetail->order_id = $request->order_id;
                $orderDetail->product_id = $request->product_id;
                $orderDetail->total_cost_detail = $request->total_cost_detail;
                $orderDetail->save();

                return response()->json([
                    'status' => 'success',
                    'messageg' => 'Tạo chi tiết đơn hàng thành công.'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function sales_statistics()
    {
        try {
            $totalSold = OrderDetail::sum('quantity');
            $salesStatistics = OrderDetail::select('product_id', DB::raw('SUM(quantity) as total_sales'))
                ->groupBy('product_id')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Sales statistics retrieved successfully',
                'total_sold' => $totalSold,
                'data' => $salesStatistics
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkUserPurchasedProduct($product_id)
    {
        try {
            // Lấy user_id của người dùng đang đăng nhập
            $user_id = auth()->id();

            // Kiểm tra xem có order_detail nào chứa product_id nhất định của người dùng không
            $orderDetail = OrderDetail::whereHas('order', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->where('product_id', $product_id)->exists();

            if ($orderDetail) {
                return response()->json([
                    'status' => 'success',
                    'message' => true
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => false
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
