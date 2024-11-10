<?php

namespace App\Http\Controllers\API\Batch;

use App\Models\Batch;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Models\BatchPromotion;
use App\Models\ProductPromotion;
use App\Http\Controllers\Controller;

class BatchPromotionController extends Controller
{
    public function getAllPromotions()
    {
        try {
            $batchPromotions = BatchPromotion::all();
            return response()->json([
                'status' => 'success',
                'data' => $batchPromotions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'promotion_id' => 'required|exists:promotions,promotion_id',
                'batch_id' => 'required|exists:batches,batch_id',
                'discount_price' => 'required|integer'
            ]);

            $promotion = Promotion::find($request->promotion_id);
            $batch = Batch::find($request->batch_id);

            $this->checkExistingPromotions($promotion, $batch);

            $batchPromotion = BatchPromotion::create($request->all());
            return response()->json([
                'status' => 'success',
                'data' => $batchPromotion
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function checkExistingPromotions(Promotion $promotion, Batch $batch)
    {
        $productId = $batch->product_id;
        $promotionExists = ProductPromotion::where('product_id', $productId)
            ->whereHas('promotion', function ($query) use ($promotion) {
                $query->where('apply_to', 'product_group')
                    ->where('end_date', '>', now())
                    ->where('promotion_id', '<>', $promotion->promotion_id);
            })
            ->exists();

        $batchPromotionExists = BatchPromotion::whereHas('batch', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })
            ->whereHas('promotion', function ($query) use ($promotion) {
                $query->where('apply_to', 'batch')
                    ->where('end_date', '>', now())
                    ->where('promotion_id', '<>', $promotion->promotion_id);
            })
            ->exists();

        if ($promotionExists || $batchPromotionExists) {
            throw new \Exception('Khuyến mãi đã tồn tại cho sản phẩm này.');
        }
    }


    public function show($id)
    {
        try {
            $batchPromotion = BatchPromotion::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $batchPromotion
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $batchPromotion = BatchPromotion::findOrFail($id);

            $request->validate([
                'promotion_id' => 'sometimes|exists:promotions,promotion_id',
                'batch_id' => 'sometimes|exists:batches,batch_id',
                'discount_price' => 'sometimes|integer'
            ]);

            $batchPromotion->update($request->all());
            return response()->json([
                'status' => 'success',
                'data' => $batchPromotion
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $batchPromotion = BatchPromotion::findOrFail($id);
            $batchPromotion->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Batch promotion deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
