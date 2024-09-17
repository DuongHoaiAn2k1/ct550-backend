<?php

namespace App\Http\Controllers\API\Promotion;

use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function index()
    {
        try {
            $promotions = Promotion::where('status', 'active')->with('product_promotion')->with('product_promotion.product')->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy danh sách khuyến mãi thành công',
                'data' => $promotions,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getById($id)
    {
        try {
            $promotions = Promotion::where('promotion_id', $id)->first();
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy khuyến mãi thành công',
                'data' => $promotions,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'promotion_name' => 'required|string|max:255',
                'discount_percentage' => 'required|integer|between:0,100',
                'user_group' => 'required|array', // đảm bảo user_group là một mảng
                'start_date' => 'required|date',
                'end_date' => 'required|date',
            ]);

            $validatedData['user_group'] = json_encode($validatedData['user_group']);

            $promotion = Promotion::create($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Tạo khuyến mãi thành công',
                'data' => $promotion,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'promotion_name' => 'sometimes|string|max:255',
                'discount_percentage' => 'sometimes|integer|between:0,100',
                'user_group' => 'sometimes|array',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date|after_or_equal:start_date',
            ]);

            $promotion = Promotion::findOrFail($id);
            $promotion->update($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật khuyến mãi thành công',
                'data' => $promotion,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function softDelete($id)
    {

        try {
            $promotion = Promotion::findOrFail($id);
            $promotion->status = 'deleted';
            $promotion->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Xoa khuyen mai thanh cong',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }




    public function destroy($id)
    {
        try {
            $promotion = Promotion::findOrFail($id);
            $promotion->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Xóa khuyến mãi thành công',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPromotionByDate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }


            $start_date = $request->start_date;
            $end_date = $request->end_date;

            $promotions = Promotion::where(function ($query) use ($start_date, $end_date) {
                $query->where('start_date', '<=', $end_date)
                    ->where('end_date', '>=', $start_date);
            })->where('status', 'active')->with('product_promotion')->with('product_promotion.product')->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Lọc khuyen mai thanh cong',
                'data' => $promotions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
