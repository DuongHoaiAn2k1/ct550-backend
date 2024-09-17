<?php

namespace App\Http\Controllers\API\Promotion;

use Illuminate\Http\Request;
use App\Models\ProductPromotion;
use App\Http\Controllers\Controller;

class ProductPromotionController extends Controller
{
    // Lấy danh sách sản phẩm với khuyến mãi
    public function index()
    {
        try {
            $productPromotions = ProductPromotion::with(['product', 'promotion'])->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy danh sách sản phẩm với khuyến mãi thành công',
                'data' => $productPromotions,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getByPromotion($promotion_id)
    {
        try {
            $productPromotions = ProductPromotion::with(['product', 'promotion'])->where('promotion_id', $promotion_id)->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Lọc danh sách san pham theo khuyen mai',
                'data' => $productPromotions,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Thêm sản phẩm với khuyến mãi
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'required',
                'promotion_id' => 'required',
                'discount_price' => 'required',
            ]);

            $productPromotion = ProductPromotion::create($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Thêm sản phẩm với khuyến mãi thành công',
                'data' => $productPromotion,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Sửa thông tin khuyến mãi của sản phẩm
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'sometimes|exists:products,product_id',
                'promotion_id' => 'sometimes|exists:promotions,promotion_id',
                'discount_price' => 'sometimes|numeric|min:0',
            ]);

            $productPromotion = ProductPromotion::findOrFail($id);
            $productPromotion->update($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật khuyến mãi của sản phẩm thành công',
                'data' => $productPromotion,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Xóa khuyến mãi của sản phẩm
    public function destroy($id)
    {
        try {
            $productPromotion = ProductPromotion::findOrFail($id);
            $productPromotion->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Xóa khuyến mãi của sản phẩm thành công',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
