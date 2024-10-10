<?php

namespace App\Http\Controllers\API\Favorite;

use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavoriteController extends Controller
{
    public function create(Request $request)
    {
        try {
            $user_id = auth()->user()->id;
            $product_id = $request->product_id;
            if (empty($product_id)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product Id is empty'
                ], 500);
            }
            $favorite = new Favorite();

            $exitsProduct = Favorite::where('user_id', $user_id)->where('product_id', $product_id)->first();

            if ($exitsProduct) {
                $exitsProduct->delete();
                return response()->json([
                    'status' => 'deleted',
                    'message' => 'Remove favorite product successfully'
                ], 200);
            }
            $favorite->user_id = $user_id;
            $favorite->product_id = $product_id;
            $favorite->save();
            return response()->json([
                'status' => 'created',
                'message' => 'Add favorite product successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get_by_user()
    {
        try {
            $user_id = auth()->user()->id;
            $list_favorite = Favorite::with('product')->where('user_id', $user_id)->get();
            $data_lenght = count($list_favorite);
            return response()->json([
                'status' => 'success',
                'data' => $list_favorite,
                'length' => $data_lenght
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function get_all()
    {
        try {
            $role = auth()->user()->roles;
            if ($role != 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Quyền này không thuộc về bạn'
                ], 500);
            }

            $list_favorite = Favorite::all();
            return response()->json([
                'status' => 'success',
                'data' => $list_favorite
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($product_id)
    {
        try {

            $deleted = Favorite::where('product_id', $product_id)->delete();

            if ($deleted == 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy sản phẩm trong danh sách yêu thích',
                    'id' => $product_id
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Xóa sản phẩm yêu thích thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
