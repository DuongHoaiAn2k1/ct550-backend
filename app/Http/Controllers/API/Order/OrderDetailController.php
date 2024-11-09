<?php

namespace App\Http\Controllers\API\Order;

use App\Models\Batch;
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
                'order_id.required' => 'Mã đơn hàng không được để trống.',
                'product_id.required' => 'Id sản phẩm không được để trống.',
                'total_cost_detail.required' => 'Tổng tiền không được để trống.'
            ];

            $validate = Validator::make($request->all(), [
                'quantity' => 'required',
                'product_id' => 'required',
                'order_id' => 'required',
                'total_cost_detail' => 'required',
                'batch_details' => 'required|array'
            ], $customMessage);

            if ($validate->fails()) {
                $errors = $validate->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'error' => $errors
                ], 422);
            } else {
                DB::beginTransaction();

                try {
                    $orderDetail = new OrderDetail();
                    $orderDetail->quantity = $request->quantity;
                    $orderDetail->order_id = $request->order_id;
                    $orderDetail->product_id = $request->product_id;
                    $orderDetail->total_cost_detail = $request->total_cost_detail;
                    $orderDetail->save();

                    foreach ($request->batch_details as $batchDetail) {
                        DB::table('order_detail_batches')->insert([
                            'order_detail_id' => $orderDetail->order_detail_id,
                            'batch_id' => $batchDetail['batch_id'],
                            'quantity' => $batchDetail['quantity'],
                        ]);
                    }

                    DB::commit();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Tạo chi tiết đơn hàng thành công.',
                        'order_detail' => $orderDetail
                    ], 200);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ], 500);
                }
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
            $user_id = auth()->id();

            // Kiểm tra xem có order_detail nào chứa product_id nhất định của người dùng không
            $orderDetail = OrderDetail::whereHas('order', function ($query) use ($user_id) {
                $query->where('user_id', $user_id)->where('status', 'delivered');
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
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function revertStock($order_id)
    {
        DB::beginTransaction(); // Bắt đầu giao dịch

        try {
            $orderDetails = OrderDetail::where('order_id', $order_id)->get();

            foreach ($orderDetails as $orderDetail) {
                $orderDetailBatches = DB::table('order_detail_batches')
                    ->where('order_detail_id', $orderDetail->order_detail_id)
                    ->get();

                foreach ($orderDetailBatches as $orderDetailBatch) {
                    $batch = Batch::find($orderDetailBatch->batch_id);

                    if ($batch) {
                        $batch->quantity += $orderDetailBatch->quantity;
                        $batch->sold_quantity -= $orderDetailBatch->quantity;
                        $batch->save();
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Stock reverted successfully for the cancelled order.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
