<?php

namespace App\Http\Controllers\API\Review;

use App\Events\Review\ReplyComment;
use App\Events\Review\ReviewCreated;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        try {
            $user_id = auth()->user()->id;

            $customMessage = [
                'product_id.required' => 'Mã sản phẩm không được để trống',
                'rating.required' => 'Đánh giá không được để trống',
                'comment.required' => 'Chi tiết đánh giá không được để trống',
            ];
            $validator = Validator::make($request->all(), [
                'product_id' => 'required',
                'rating' => 'required',
                'comment' => 'required'
            ], $customMessage);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'erros' => $errors
                ], 422);
            } else {
                if (Review::where('product_id', $request->product_id)->where('user_id', $user_id)->exists()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Không được đánh giá lại'
                    ], 500);
                } else {
                    $review = new Review();
                    $review->rating = $request->rating;
                    $review->comment = $request->comment;
                    $review->product_id = $request->product_id;
                    $review->user_id = $user_id;
                    $review->save();
                    event(new ReviewCreated());
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Tạo đánh giá thành công'
                    ], 200);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function reply(Request $request, $id)
    {
        try {
            $reply = $request->reply;
            $review = Review::where('review_id', $id)->first();
            $review->reply = $reply;
            $review->save();

            event(new ReplyComment());
            return response()->json([
                'status' => 'success',
                'message' => 'Trả lời đánh giá thành công',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateReview(Request $request, $id)
    {
        try {
            $review = Review::where('review_id', $id)->first();
            $userId = auth()->user()->id;
            if ($review->user_id != $userId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bạn không phải quyền thay đổi đánh giá'
                ], 500);
            }
            $review->rating = $request->rating;
            $review->comment = $request->comment;
            $review->save();
            event(new ReviewCreated());
            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật đánh giá thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAll()
    {
        try {
            // $review = new Review();
            $listReview = Review::all();
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy danh sách đánh giá thành công',
                'data' => $listReview
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function delete($id)
    {
        try {
            $review = Review::where('review_id', $id)->first();

            if (!$review) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy đánh giá'
                ], 404);
            }
            // Kiểm tra xem người dùng hiện tại có quyền xóa đánh giá hay không
            // Ở đây, giả sử chỉ người dùng đã tạo đánh giá mới có quyền xóa nó
            // if (auth()->user()->roles != 0) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Bạn không có quyền xóa đánh giá này'
            //     ], 403);
            // }

            $review->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Xóa đánh giá thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getByProductId($productId)
    {
        try {
            $reviews = Review::where('product_id', $productId)->with('user')->get();

            if ($reviews->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Không có đánh giá nào cho sản phẩm này'
                ], 200);
            }

            // Tính tổng điểm đánh giá
            $totalRating = $reviews->sum('rating');
            $totalReviews = $reviews->count();
            $averageRating = $totalReviews > 0 ? $totalRating / $totalReviews : 0;
            $dataLength = count($reviews);

            // Trả về danh sách đánh giá với thông tin của người dùng đã đánh giá
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy danh sách đánh giá thành công',
                'data' => $reviews,
                'average_rating' => $averageRating,
                'length' => $dataLength
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function userHasReviewedProduct($productId)
    {
        try {
            $userId = auth()->user()->id;

            $reviewExists = Review::where('product_id', $productId)
                ->where('user_id', $userId)
                ->exists();

            return response()->json([
                'status' => 'success',
                'message' => $reviewExists ? 'Người dùng đã đánh giá sản phẩm này' : 'Người dùng chưa đánh giá sản phẩm này',
                'data' => $reviewExists
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
