<?php

namespace App\Http\Controllers\API\Promotion;

use DateTime;
use App\Models\Batch;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Models\BatchPromotion;
use App\Models\ProductPromotion;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function index()
    {
        try {
            $promotions = Promotion::with('product_promotion')->with('batch_promotion')->with('batch_promotion.batch')->with('batch_promotion.batch.product')->with('product_promotion.product')->get();
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

    public function storeBatch(Request $request)
    {
        try {
            // Bắt đầu giao dịch
            DB::beginTransaction();

            // Validate the incoming request data
            $validatedData = $request->validate([
                'promotion_name' => 'required|string|max:255',
                'discount_percentage' => 'required|integer|between:0,100',
                'user_group' => 'required|array', // Ensure user_group is an array
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'batch_select' => 'required|array', // Ensure batch_select is an array
            ]);

            // Encode the user group data to JSON format
            $validatedData['user_group'] = json_encode($validatedData['user_group']);

            // Create a new Promotion object and save it
            $promotion = new Promotion();
            $promotion->promotion_name = $validatedData['promotion_name'];
            $promotion->discount_percentage = $validatedData['discount_percentage'];
            $promotion->user_group = $validatedData['user_group'];
            $promotion->apply_to = 'batch';
            $promotion->start_date = new DateTime($validatedData['start_date']);
            $promotion->end_date = new DateTime($validatedData['end_date']);
            $promotion->save();

            // Kiểm tra nếu `promotion` không có `id`
            if (!$promotion->promotion_id) {
                throw new \Exception('Failed to create promotion. ID is null.');
            }

            $createdBatchPromotions = []; // Mảng để lưu trữ các BatchPromotion đã tạo

            // Create BatchPromotion instances and associate them with the promotion
            foreach ($validatedData['batch_select'] as $batchId) {
                $product_id = Batch::where('batch_id', $batchId)->first()->product_id;
                $product = Product::find($product_id);
                $productPromotion = ProductPromotion::where('product_id', $product_id)->with('promotion')->first();
                if ($productPromotion && $productPromotion->promotion->status == 'active') {
                    // Rollback if a conflict is detected
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Đã tồn tại khuyến mãi cho sản phẩm ' . $product->name,
                        'error' => 'exists',
                        'product' => $product
                    ], 409);
                }

                $batchPromotion = new BatchPromotion();
                $batchPromotion->batch_id = $batchId;
                $batchPromotion->promotion_id = $promotion->promotion_id;
                $batchPromotion->discount_price = $product->product_price * $validatedData['discount_percentage'] / 100;
                // Lưu BatchPromotion và thêm vào mảng nếu thành công
                $batchPromotion->save();
                $createdBatchPromotions[] = $batchPromotion;
            }

            // Commit the transaction if everything is successful
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Tạo khuyến mãi thành công',
                'data' => $promotion,
            ], 201);
        } catch (\Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollBack();

            // Xóa các bản ghi BatchPromotion đã tạo nếu có lỗi
            foreach ($createdBatchPromotions as $createdBatchPromotion) {
                $createdBatchPromotion->delete();
            }

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

    public function endPromotion($id)
    {
        try {
            $promotion = Promotion::findOrFail($id);
            $promotion->status = 'unactive';
            $promotion->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Ket thuc khuyen mai thanh cong',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
