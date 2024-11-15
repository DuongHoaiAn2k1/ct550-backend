<?php

namespace App\Http\Controllers\API\Batch;

use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\ReduceProductQuantity;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BatchController extends Controller
{
    public function show($batch_id)
    {
        try {
            $batch = Batch::find($batch_id);
            if (!$batch) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Batch not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $batch
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $batches = Batch::where('status', 'Active')->with('product')->with('user')->get();

            return response()->json([
                'status' => 'success',
                'data' => $batches
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getHiddenList()
    {
        try {
            $batches = Batch::where('status', 'Hidden')->with('product')->with('user')->get();

            return response()->json([
                'status' => 'success',
                'data' => $batches
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getExpiringSoon()
    {
        try {
            $batches = Batch::where('status', 'Expiring Soon')->with('product')->with('user')->get();

            return response()->json([
                'status' => 'success',
                'data' => $batches
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getExpired()
    {
        try {
            $batches = Batch::where('status', 'Expired')->with('product')->with('user')->get();
            return response()->json([
                'status' => 'success',
                'data' => $batches

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
        $customMessage = [
            'product_id.required' => 'Sản phẩm không được để trống',
            'quantity.required' => 'Số lượng không được để trống',
            'entry_date.required' => 'Ngày nhập không được để trống',
            'expiry_date.required' => 'Ngày hết hạn không được để trống',
            'batch_cost.required' => 'Giá nhập không được để trống',
        ];

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
            'entry_date' => 'required',
            'expiry_date' => 'required',
            'batch_cost' => 'required|integer|min:0',
        ], $customMessage);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $batch = new Batch();
            $batch->product_id = $request->product_id;
            $batch->quantity = $request->quantity;
            $batch->entry_date = $request->entry_date;
            $batch->expiry_date = $request->expiry_date;
            $batch->user_id = auth()->user()->id;
            $batch->batch_cost = $request->batch_cost;
            $batch->sold_quantity = 0;
            $batch->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Created batch successfully',
                'data' => $batch
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Phương thức để cập nhật thông tin lô hàng
    public function update(Request $request, $batch_id)
    {
        $customMessage = [
            'quantity.required' => 'Số lượng không được để trống',
            'entry_date.required' => 'Ngày nhập không được để trống',
            'expiry_date.required' => 'Ngày hết hạn không được để trống',
            'batch_cost.required' => 'Giá không được là bằng 0',
        ];

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
            'entry_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:entry_date',
            'batch_cost' => 'required|integer|min:0',
        ], $customMessage);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $batch = Batch::find($batch_id);
            if (!$batch) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Batch not found'
                ], 404);
            }

            $batch->quantity = $request->quantity;
            $batch->entry_date = $request->entry_date;
            $batch->expiry_date = $request->expiry_date;
            $batch->batch_cost = $request->batch_cost;
            $batch->user_id = auth()->user()->id;
            $batch->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Updated batch successfully',
                'data' => $batch
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($batch_id)
    {
        try {
            $batch = Batch::find($batch_id);
            if (!$batch) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Batch not found'
                ], 404);
            }

            $batch->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Deleted batch successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function reduceStock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $product_id = $request->product_id;
            $quantity_to_reduce = $request->quantity;

            $date_threshold = now()->addDays(15);

            $batches = Batch::where('product_id', $product_id)->where('status', 'Active')
                ->where('expiry_date', '>', $date_threshold)
                ->orderBy('entry_date', 'asc')
                ->lockForUpdate()
                ->get();


            $batchDetails = [];

            foreach ($batches as $batch) {
                if ($quantity_to_reduce <= 0) {
                    break;
                }

                if ($batch->quantity >= $quantity_to_reduce) {
                    $batch->quantity -= $quantity_to_reduce;
                    $batch->sold_quantity += $quantity_to_reduce;
                    $batch->save();

                    $batchDetails[] = [
                        'batch_id' => $batch->batch_id,
                        'quantity' => $quantity_to_reduce
                    ];
                    $quantity_to_reduce = 0;
                } else {
                    $quantity_to_reduce -= $batch->quantity;

                    $batchDetails[] = [
                        'batch_id' => $batch->batch_id,
                        'quantity' => $batch->quantity
                    ];
                    $batch->sold_quantity += $batch->quantity;
                    $batch->quantity = 0;
                    $batch->save();
                }
            }

            if ($quantity_to_reduce > 0) {
                DB::rollBack();
                return response()->json([
                    'status' => 'not_enough_stock',
                    'message' => 'Not enough stock to reduce the requested quantity'
                ], 400);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Reduced stock successfully',
                'batchDetails' => $batchDetails
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkStockAvailability(Request $request)
    {
        $products = $request->input('products', []); // Mảng sản phẩm từ request

        $results = [];

        foreach ($products as $product) {
            $product_id = $product['product_id'];
            $required_quantity = $product['quantity'];

            $total_available_quantity = Batch::where('product_id', $product_id)->where('status', 'Active')
                ->where('expiry_date', '>', now()->addDays(15)) // Chỉ tính các lô hàng có hạn sử dụng trên 15 ngày
                ->sum('quantity');

            if ($total_available_quantity < $required_quantity) {
                // Nếu tổng số lượng có sẵn nhỏ hơn số lượng yêu cầu, thêm vào mảng kết quả
                $results[] = [
                    'product_id' => $product_id,
                    'required_quantity' => $required_quantity,
                    'available_quantity' => $total_available_quantity,
                    'status' => 'not_enough_stock'
                ];
            }
        }

        if (count($results) > 0) {
            // Nếu có sản phẩm không đủ số lượng, trả về chi tiết
            return response()->json([
                'status' => 'Not enough stock',
                'message' => 'Some products do not have enough stock',
                'details' => $results
            ], 400);
        }

        // Nếu tất cả sản phẩm đều đủ số lượng
        return response()->json([
            'status' => 'success',
            'message' => 'All products have enough stock'
        ], 200);
    }

    public function checkProductInStock($product_id)
    {
        try {
            $total_available_quantity = Batch::where('product_id', $product_id)
                ->where('expiry_date', '>', now()->addDays(15)) // Chỉ lấy các lô hàng có hạn sử dụng trên 15 ngày
                ->sum('quantity');

            if ($total_available_quantity > 0) {
                return response()->json([
                    'status' => 'success',
                    'data' => true,
                    'available_quantity' => $total_available_quantity
                ], 200);
            }

            return response()->json([
                'status' => 'out_of_stock',
                'data' => false,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus($batch_id, Request $request)
    {
        try {
            $batch = Batch::find($batch_id);
            $batch->status = $request->status;
            $batch->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Update status successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
