<?php

namespace App\Http\Controllers\API\Product;

use App\Models\Batch;
use App\Models\Review;
use App\Models\Product;
use App\Models\Category;
use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('product_promotion', 'product_promotion.promotion')
                ->get()
                ->map(function ($product) {
                    // Tính tổng số lượng còn trong kho từ các lô hàng còn hạn trên 20 ngày
                    $available_quantity = (int) Batch::where('product_id', $product->product_id)->where('status', 'Active')
                        ->sum('quantity');

                    // Tính trung bình rating
                    $average_rating = (float) Review::where('product_id', $product->product_id)
                        ->avg('rating');

                    // Thêm các thuộc tính vào sản phẩm
                    $product->available_quantity = $available_quantity;
                    $product->average_rating = $average_rating ? round($average_rating, 2) : null; // Làm tròn đến 2 chữ số thập phân

                    if (auth()->check()) {
                        $user_id = auth()->user()->id;
                        $roles = auth()->user()->getRoleNames();

                        $mainRole = $roles->filter(function ($role) {
                            return $role !== 'affiliate_marketer';
                        })->first();
                        // Kiểm tra xem sản phẩm này có nằm trong danh sách yêu thích của người dùng hay không
                        $is_favorite = Favorite::where('user_id', $user_id)
                            ->where('product_id', $product->product_id)
                            ->exists();

                        $product->liked = $is_favorite;

                        $product->product_promotion = $product->product_promotion->filter(function ($promotion) use ($mainRole) {
                            $user_groups = json_decode($promotion->promotion->user_group, true);

                            if (is_array($user_groups) && in_array($mainRole, $user_groups)) {
                                return true;
                            }
                            return false;
                        });

                        if ($product->product_promotion->isEmpty()) {
                            unset($product->product_promotion);
                        }
                    } else {
                        $product->liked = false;
                        unset($product->product_promotion);
                    }
                    return $product;
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy sản phẩm thành công',
                'listProduct' => $products,
                'length' => $products->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get($id)
    {
        try {
            $products = Product::where('product_id', $id)
                ->with([
                    'product_promotion' => function ($query) {
                        $query->whereHas('promotion', function ($q) {
                            $q->where('status', 'active');
                        });
                    },
                    'product_promotion.promotion',
                    'batches' => function ($query) {
                        $query->whereHas('batchPromotion', function ($q) {
                            $q->whereHas('promotion', function ($q) {
                                $q->where('status', 'active');
                            });
                        });
                    },
                    'batches.batchPromotion.promotion'
                ])
                ->get()
                ->map(function ($product) {
                    // Tính tổng số lượng còn trong kho từ các lô hàng còn hạn trên 15 ngày
                    $available_quantity = (int) Batch::where('product_id', $product->product_id)
                        ->where('expiry_date', '>', now()->addDays(15))
                        ->sum('quantity');

                    $expiring_soon_quantity = (int) Batch::where('product_id', $product->product_id)
                        ->where('status', 'Expiring Soon')
                        ->whereHas('batchPromotion')->whereHas('batchPromotion.promotion', function ($query) {
                            $query->where('status', 'active');
                        })
                        ->sum('quantity');

                    // Tổng số lượng khả dụng sẽ bao gồm cả 'Active' và 'Expiring Soon' có batch_promotion
                    $available_quantity += $expiring_soon_quantity;

                    // Tính tổng số lượng từ các batch có batch_promotion
                    $product_quantity_batch_promotion = $product->batches->reduce(function ($carry, $batch) {
                        return $carry + $batch->quantity;
                    }, 0);

                    // Tính trung bình rating
                    $average_rating = Review::where('product_id', $product->product_id)
                        ->avg('rating');

                    // Thêm các thuộc tính vào sản phẩm
                    $product->available_quantity = $available_quantity;
                    $product->average_rating = $average_rating ? round($average_rating, 2) : null;

                    if ($product->batches->isEmpty()) {
                        unset($product->batches);
                    }
                    if (auth()->check()) {
                        $user_id = auth()->user()->id;
                        $roles = auth()->user()->getRoleNames();

                        $mainRole = $roles->filter(function ($role) {
                            return $role !== 'affiliate_marketer';
                        })->first();
                        $is_favorite = Favorite::where('user_id', $user_id)
                            ->where('product_id', $product->product_id)
                            ->exists();

                        $product->liked = $is_favorite;

                        $product->product_promotion = $product->product_promotion->filter(function ($promotion) use ($mainRole) {
                            $user_groups = json_decode($promotion->promotion->user_group, true);

                            if (is_array($user_groups) && in_array($mainRole, $user_groups)) {
                                return true;
                            }
                            return false;
                        });

                        if ($product->product_promotion->isEmpty()) {
                            unset($product->product_promotion);
                        }
                    } else {
                        $product->liked = false;
                        unset($product->product_promotion);
                    }
                    return $product;
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy sản phẩm thành công',
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function getProductByCategoryName(Request $request)
    {
        try {
            $category_name = $request->category_name;
            $category = Category::where('category_name', $category_name)->first();

            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found'
                ], 404);
            }

            // Lấy danh sách sản phẩm theo category_id với các quan hệ cần thiết
            $products = Product::where('category_id', $category->category_id)
                ->with([
                    'product_promotion' => function ($query) {
                        $query->whereHas('promotion', function ($q) {
                            $q->where('status', 'active');
                        });
                    },
                    'product_promotion.promotion',
                    'batches' => function ($query) {
                        $query->whereHas('batchPromotion', function ($q) {
                            $q->whereHas('promotion', function ($q) {
                                $q->where('status', 'active');
                            });
                        });
                    },
                    'batches.batchPromotion.promotion'
                ])
                ->get()
                ->map(function ($product) {
                    // Tính tổng số lượng còn trong kho từ các lô hàng còn hạn trên 15 ngày
                    $available_quantity = (int) Batch::where('product_id', $product->product_id)
                        ->where('expiry_date', '>', now()->addDays(15))
                        ->sum('quantity');

                    // Tính thêm số lượng từ các batch có trạng thái 'Expiring Soon' và có batchPromotion
                    $expiring_soon_quantity = (int) Batch::where('product_id', $product->product_id)
                        ->where('status', 'Expiring Soon')
                        ->whereHas('batchPromotion')->whereHas('batchPromotion.promotion', function ($query) {
                            $query->where('status', 'active');
                        })
                        ->sum('quantity');

                    // Tổng số lượng khả dụng sẽ bao gồm cả 'Active' và 'Expiring Soon' có batch_promotion
                    $available_quantity += $expiring_soon_quantity;


                    // Tính tổng số lượng từ các batch có batch_promotion
                    $product_quantity_batch_promotion = $product->batches->reduce(function ($carry, $batch) {
                        return $carry + $batch->quantity;
                    }, 0);

                    // Tính trung bình rating
                    $average_rating = (float) Review::where('product_id', $product->product_id)
                        ->avg('rating');

                    // Thêm các thuộc tính vào sản phẩm
                    $product->available_quantity = $available_quantity;
                    $product->product_quantity_batch_promotion = $product_quantity_batch_promotion;
                    $product->average_rating = $average_rating ? round($average_rating, 2) : null;

                    // Các 'batches' đã được lọc từ truy vấn, nhưng bạn có thể thêm kiểm tra bổ sung nếu cần
                    if ($product->batches->isEmpty()) {
                        unset($product->batches);
                    }

                    // Kiểm tra nếu người dùng đã đăng nhập
                    if (auth()->check()) {
                        $user_id = auth()->user()->id;
                        $roles = auth()->user()->getRoleNames();

                        $mainRole = $roles->filter(function ($role) {
                            return $role !== 'affiliate_marketer';
                        })->first();

                        // Kiểm tra xem sản phẩm này có nằm trong danh sách yêu thích của người dùng hay không
                        $is_favorite = Favorite::where('user_id', $user_id)
                            ->where('product_id', $product->product_id)
                            ->exists();

                        // Thêm trường 'liked' cho sản phẩm
                        $product->liked = $is_favorite;

                        $product->product_promotion = $product->product_promotion->filter(function ($promotion) use ($mainRole) {
                            $user_groups = json_decode($promotion->promotion->user_group, true);

                            if (is_array($user_groups) && in_array($mainRole, $user_groups)) {
                                return true;
                            }
                            return false;
                        });

                        if ($product->product_promotion->isEmpty()) {
                            unset($product->product_promotion);
                        }
                    } else {
                        // Nếu người dùng không đăng nhập, trường 'liked' sẽ là false
                        $product->liked = false;
                        unset($product->product_promotion);
                    }

                    return $product;
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy sản phẩm thành công',
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    // public function getProductByName(Request $request)
    // {
    //     try {
    //         $productName = $request->product_name;
    //         if (empty($productName)) {
    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'Lấy danh sách sản phẩm thành công',
    //                 'data' => ""
    //             ], 200);
    //         }
    //         $products = Product::where('product_name', 'like', "%$productName%")->get();

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Lấy danh sách sản phẩm thành công',
    //             'data' => $products
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function decreaseProductQuantity(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,product_id',
                'quantity' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ], 422);
            } else {
                $product = Product::find($request->product_id);

                if (!$product) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Product not found'
                    ], 404);
                }

                $newQuantity = $product->product_quantity - $request->quantity;
                if ($newQuantity < 0) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Not enough quantity for product: ' . $product->product_name
                    ], 422);
                }

                $product->product_quantity = $newQuantity;
                $product->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Decreased product quantity successfully'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function increaseProductQuantity(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                '*.product_id' => 'required|exists:products,product_id',
                '*.quantity' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ], 422);
            } else {
                foreach ($request->all() as $item) {
                    $product = Product::find($item['product_id']);

                    if (!$product) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Product not found'
                        ], 404);
                    }

                    $newQuantity = $product->product_quantity + $item['quantity'];

                    $product->product_quantity = $newQuantity;
                    $product->save();
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Increased product quantities successfully'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }





    public function getProductByCategoryId($category_id)
    {
        try {
            $products = Product::where('category_id', $category_id)
                ->with([
                    'product_promotion' => function ($query) {
                        $query->whereHas('promotion', function ($q) {
                            $q->where('status', 'active');
                        });
                    },
                    'product_promotion.promotion',
                    'batches' => function ($query) {
                        $query->whereHas('batchPromotion', function ($q) {
                            $q->whereHas('promotion', function ($q) {
                                $q->where('status', 'active');
                            });
                        });
                    },
                    'batches.batchPromotion.promotion',
                ])
                ->get()
                ->map(function ($product) {
                    // Tính tổng số lượng còn trong kho từ các lô hàng còn hạn trên 20 ngày
                    $available_quantity = (int) Batch::where('product_id', $product->product_id)->where('status', 'Active')
                        ->sum('quantity');

                    $expiring_soon_quantity = (int) Batch::where('product_id', $product->product_id)
                        ->where('status', 'Expiring Soon')
                        ->whereHas('batchPromotion')->whereHas('batchPromotion.promotion', function ($query) {
                            $query->where('status', 'active');
                        })
                        ->sum('quantity');

                    $available_quantity += $expiring_soon_quantity;

                    $product_quantity_batch_promotion = $product->batches->reduce(function ($carry, $batch) {
                        return $carry + $batch->quantity;
                    }, 0);

                    // Tính trung bình rating
                    $average_rating = (float) Review::where('product_id', $product->product_id)
                        ->avg('rating');

                    // Thêm các thuộc tính vào sản phẩm
                    $product->available_quantity = $available_quantity;
                    $product->product_quantity_batch_promotion = $product_quantity_batch_promotion;
                    $product->average_rating = $average_rating ? round($average_rating, 2) : null; // Làm tròn đến 2 chữ số thập phân

                    if ($product->batches->isEmpty()) {
                        unset($product->batches);
                    }

                    if (auth()->check()) {
                        $user_id = auth()->user()->id;
                        $roles = auth()->user()->getRoleNames();

                        $mainRole = $roles->filter(function ($role) {
                            return $role !== 'affiliate_marketer';
                        })->first();

                        // Kiểm tra xem sản phẩm này có nằm trong danh sách yêu thích của người dùng hay không
                        $is_favorite = Favorite::where('user_id', $user_id)
                            ->where('product_id', $product->product_id)
                            ->exists();

                        // Thêm trường 'liked' cho sản phẩm
                        $product->liked = $is_favorite;

                        $product->product_promotion = $product->product_promotion->filter(function ($promotion) use ($mainRole) {
                            $user_groups = json_decode($promotion->promotion->user_group, true);

                            if (is_array($user_groups) && in_array($mainRole, $user_groups)) {
                                return true;
                            }
                            return false;
                        });

                        if ($product->product_promotion->isEmpty()) {
                            unset($product->product_promotion);
                        }
                    } else {
                        // Nếu người dùng không đăng nhập, trường 'liked' sẽ là false
                        $product->liked = false;
                        unset($product->product_promotion);
                    }
                    return $product;
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy sản phẩm thành công',
                'data' => $products
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
            'product_name.required' => 'Tên sản phẩm không được để trống',
            'product_img.required' => 'Hình ảnh không được để trống',
            'product_des.require' => 'Chi tiết sản phẩm không được để trống',
            'category_id.required' => 'Danh mục không được để trống',
            'product_price.require' => 'Giá sản phẩm không được để trống',
            'weight.required' => 'Khối lượng không được để trống',

        ];

        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'product_img' => 'required',
            'product_des' => 'required',
            'category_id' => 'required',
            'product_price' => 'required',
            'weight' => 'required',
        ], $customMessage);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $errors
            ], 422);
        } else {
            try {
                if (Product::where('product_name', $request->product_name)->exists()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Tên sản phẩm đã tồn tại'
                    ], 422);
                } else {
                    $imagePaths = [];
                    foreach ($request->file('product_img') as $image) {
                        $imagePath = $image->store('uploads', 'public');
                        $imagePaths[] = $imagePath;
                    }
                    $product = new Product();
                    $product->product_name = $request->product_name;
                    $product->product_img = json_encode($imagePaths, JSON_UNESCAPED_SLASHES);
                    $product->product_des = $request->product_des;
                    $product->category_id = $request->category_id;
                    $product->product_price = $request->product_price;
                    $product->weight = $request->weight;
                    $product->save();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Create product successfully'
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

    public function update(Request $request, $product_id)
    {
        $customMessage = [
            'product_name.required' => 'Tên sản phẩm không được để trống',
            'product_des.required' => 'Chi tiết sản phẩm không được để trống',
            'category_id.required' => 'Danh mục không được để trống',
            'product_price.required' => 'Giá sản phẩm không được để trống',
            'weight.required' => 'Khối lượng không được để trống',

        ];

        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'product_des' => 'required',
            'category_id' => 'required',
            'product_price' => 'required',
            'weight' => 'required',
        ], $customMessage);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $errors
            ], 422);
        } else {
            try {
                $existProduct = Product::where('product_name', $request->product_name)
                    ->where('product_id', $product_id)
                    ->first();
                if (!$existProduct) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Sản phẩm không tồn tại'
                    ], 422);
                } else {
                    $product = Product::find($product_id);

                    if ($request->hasFile('product_img')) {
                        // Delete old images
                        $oldImages = json_decode($product->product_img, true);
                        foreach ($oldImages as $oldImage) {
                            Storage::disk('public')->delete($oldImage);
                        }

                        // Store new images
                        $imagePaths = [];
                        foreach ($request->file('product_img') as $image) {
                            $imagePath = $image->store('uploads', 'public');
                            $imagePaths[] = $imagePath;
                        }
                        $product->product_img = json_encode($imagePaths, JSON_UNESCAPED_SLASHES);
                    }

                    $product->product_name = $request->product_name;
                    $product->product_des = $request->product_des;
                    $product->category_id = $request->category_id;
                    $product->product_price = $request->product_price;
                    $product->weight = $request->weight;
                    $product->save();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Updated product successfully'
                    ], 201);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'request' => $request->all()
                ], 500);
            }
        }
    }

    public function delete($product_id)
    {
        try {
            $product = Product::find($product_id);

            if ($product) {
                // Delete images
                $oldImages = json_decode($product->product_img, true);
                foreach ($oldImages as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }

                // Delete product
                $product->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Xóa sản phẩm thành công'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getPrice($product_id)
    {
        try {
            $product = Product::where('product_id', $product_id)->first();
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy giá tiền thành công',
                'price' => $product['product_price']
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function increase_product_view_count($product_id)
    {
        try {
            $product = Product::find($product_id);

            if ($product) {
                $product->increment('product_views');

                return response()->json([
                    'status' => 'success',
                    'message' => 'Increased product view count successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateQuantity(Request $request, $product_id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_quantity' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ], 422);
            } else {
                $product = Product::find($product_id);

                if (!$product) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Product not found'
                    ], 404);
                }

                $product->product_quantity = $request->product_quantity;
                $product->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Updated product quantity successfully'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getProductsWithReviews()
    {
        try {
            $products = Product::with('review.user')->get();

            foreach ($products as $product) {
                $totalRating = 0;
                $totalReviews = $product->review->count();

                foreach ($product->review as $review) {
                    $totalRating += $review->rating;
                }

                $averageRating = $totalReviews > 0 ? $totalRating / $totalReviews : 0;

                // Thêm dữ liệu đánh giá và tổng điểm trung bình vào thuộc tính của sản phẩm
                $product->setAttribute('total_rating',  $totalReviews);
                $product->setAttribute('reviews', $product->review);
                $product->setAttribute('average', $averageRating);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy danh sách sản phẩm và đánh giá thành công',
                'listProduct' => $products,
                'length' => $products->count()
            ], 200);
        } catch (\Exception $e) {
            // Xử lý ngoại lệ nếu có
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function searchAI(Request $request)
    {
        try {
            // Lấy giá trị 'query' từ request
            $query = $request->input('query');

            if (empty($query)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Trường query không được để trống'
                ], 400);
            }

            // Gửi dữ liệu JSON đến API Python
            $client = new \GuzzleHttp\Client();
            $response = $client->post('http://python-search:8000/api/search/all', [
                'json' => [
                    'query' => $query
                ]
            ]);

            // Lấy danh sách sản phẩm trả về từ API Python
            $body = json_decode($response->getBody(), true);

            // Lấy danh sách product_id từ kết quả trả về
            $productIds = array_column($body, 'product_id');

            // Truy vấn cơ sở dữ liệu để lấy thông tin chi tiết của các sản phẩm
            $products = Product::whereIn('product_id', $productIds)->with('product_promotion', 'product_promotion.promotion')
                ->get()
                ->map(function ($product) {
                    // Tính tổng số lượng còn trong kho từ các lô hàng còn hạn trên 20 ngày
                    $available_quantity = (int) Batch::where('product_id', $product->product_id)->where('status', 'Active')
                        ->sum('quantity');

                    // Tính trung bình rating
                    $average_rating = (float) Review::where('product_id', $product->product_id)
                        ->avg('rating');

                    // Thêm các thuộc tính vào sản phẩm
                    $product->available_quantity = $available_quantity;
                    $product->average_rating = $average_rating ? round($average_rating, 2) : null; // Làm tròn đến 2 chữ số thập phân

                    if (auth()->check()) {
                        $user_id = auth()->user()->id;
                        // Kiểm tra xem sản phẩm này có nằm trong danh sách yêu thích của người dùng hay không
                        $is_favorite = Favorite::where('user_id', $user_id)
                            ->where('product_id', $product->product_id)
                            ->exists();

                        // Thêm trường 'like' cho sản phẩm
                        $product->liked = $is_favorite;
                    } else {
                        // Nếu người dùng không đăng nhập, trường 'like' sẽ là false
                        $product->liked = false;
                    }
                    return $product;
                });

            // Kết hợp thông tin sản phẩm với similarity
            $result = [];
            foreach ($products as $product) {
                // Tìm similarity cho product_id tương ứng
                $matchingProduct = collect($body)->firstWhere('product_id', $product->product_id);

                // Kiểm tra nếu tìm thấy product_id trong kết quả trả về từ API Python
                $similarity = $matchingProduct ? $matchingProduct['similarity'] : null;

                $result[] = [
                    'product' => $product,
                    'similarity' => $similarity
                ];
            }

            usort($result, function ($a, $b) {
                return $b['similarity'] <=> $a['similarity'];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy danh sách sản phẩm từ API thành công',
                'data' => $result,
                'length' => count($result)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function searchAIMini(Request $request)
    {
        try {
            $query = $request->input('query');
            if (empty($query)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Trường query không được để trống'
                ], 400);
            }
            $client = new \GuzzleHttp\Client();
            $response = $client->post('http://python-search:8000/api/search/', [
                'json' => [
                    'query' => $query
                ]
            ]);
            $body = json_decode($response->getBody(), true);
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy dữ liệu từ API thành công',
                'data' => $body
            ], 200);
        } catch (\Exception $e) {
            // Xử lý lỗi và trả về thông báo lỗi
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
