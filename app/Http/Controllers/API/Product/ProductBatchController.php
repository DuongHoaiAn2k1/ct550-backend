<?php

namespace App\Http\Controllers\API\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductBatchController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('batches')->get();

            $total_quantity = $products->flatMap(function ($product) {
                return $product->batches;
            })->sum('quantity');

            $products = $products->map(function ($product) {
                // Tính tổng quantity từ các batches của sản phẩm
                $product->product_quantity = $product->batches->where('status', 'Active')->sum('quantity');
                return $product;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy danh sách sản phẩm thành công + batches',
                'data' => $products,
                'total_batches_quantity' => $total_quantity,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
