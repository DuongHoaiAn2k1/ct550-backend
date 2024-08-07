<?php

namespace App\Http\Controllers\API\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $listCategory = Category::get();
            $dataLength = count($listCategory);
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy toàn bộ danh mục thành công',
                'listCategory' => $listCategory,
                'length' => $dataLength
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get($category_id)
    {
        try {
            $category = Category::where('category_id', $category_id)->first();
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy danh mục thành công',
                'category' => $category
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
        $customeMessage = [
            'category_name.required' => 'Tên danh mục không được để trống',
        ];

        $validator = Validator::make($request->all(), [
            'category_name' => 'required'
        ], $customeMessage);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $errors
            ], 422);
        } else {
            try {
                if (Category::where('category_name', $request->category_name)->exists()) {
                    return response()->json([
                        'message' => 'error',
                        'message' => 'Danh mục đã tồn tại'
                    ], 422);
                } else {
                    $category = new Category();
                    $category->category_name = $request->category_name;
                    $category->save();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Thêm danh mục thành công'
                    ], 201);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => "error",
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function update(Request $request, $category_id)
    {
        // return response()->json([
        //     'data' => $request->category_name
        // ]);
        $customeMessage = [
            'category_name.required' => 'Tên danh mục không được để trống',
        ];


        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
        ], $customeMessage);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $errors
            ], 422);
        } else {
            try {
                $category = Category::where('category_id', $category_id)->first();

                if (!$category) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Danh mục không tồn tại',
                    ], 404);
                }

                $existingCategory = Category::where('category_name', $request->category_name)->where('category_id', '<>', $category_id)->first();
                if ($existingCategory) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Danh mục đã tồn tại',
                    ], 422);
                } else {
                    Category::where('category_id', $category_id)->update([
                        'category_name' => $request->category_name,
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Cập nhật danh mục thành công'
                    ], 201);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function delete($category_id)
    {
        try {
            Category::where('category_id', $category_id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Xoá danh mục thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
