<?php

namespace App\Http\Controllers\API\Batch;

use App\Models\Batch;
use Illuminate\Http\Request;
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
            $batches = Batch::with('product')->get();

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
}
