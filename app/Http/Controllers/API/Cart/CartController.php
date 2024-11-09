<?php

namespace App\Http\Controllers\API\Cart;

use App\Models\Cart;
use App\Models\Batch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\Batch\BatchController;

class CartController extends Controller
{
    public function index()
    {
        return "Hi";
    }

    public function get()
    {
        try {
            $user_id = auth()->user()->id;
            $listCart = Cart::where('user_id', $user_id)
                ->with([
                    'product',
                    'product.product_promotion',
                    'product.product_promotion.promotion',
                    'product.batches' => function ($query) {
                        $query->whereHas('batchPromotion', function ($q) {
                            $q->whereHas('promotion', function ($q) {
                                $q->where('status', 'active');
                            });
                        });
                    },
                    'product.batches.batchPromotion.promotion'
                ])
                ->get()
                ->map(function ($cartItem) {
                    $product = $cartItem->product;
                    if (auth()->check()) {
                        $user_id = auth()->user()->id;
                        $roles = auth()->user()->getRoleNames();

                        $mainRole = $roles->filter(function ($role) {
                            return $role !== 'affiliate_marketer';
                        })->first();
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

                        if ($product->batches->isEmpty()) {
                            unset($product->batches);
                        }
                    } else {
                        unset($product->product_promotion);
                    }

                    $cartItem->product = $product;
                    return $cartItem;
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy giỏ hàng thành công',
                'data' => $listCart
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    // public function create(Request $request)
    // {
    //     try {
    //         $existsCart = Cart::where('user_id', $request->user_id)->where('product_id', $request->product_id)->orderBy('created_at', 'desc')->first();
    //         if (!$existsCart) {
    //             $cart = new Cart();
    //             $cart->user_id = $request->user_id;
    //             $cart->product_id = $request->product_id;
    //             $cart->quantity = $request->quantity;
    //             $cart->total_weight = $request->total_weight;
    //             $cart->save();
    //         } else {
    //             if ($existsCart['quantity'] + $request->quantity <= 10) {
    //                 Cart::where('cart_id', $existsCart['cart_id'])->update([
    //                     'quantity' => $existsCart['quantity'] + $request->quantity,
    //                     'total_weight' => $existsCart['total_weight'] + $request->total_weight
    //                 ]);
    //             } else {
    //                 return response()->json([
    //                     'status' => 'error',
    //                     'message' => 'Qúa số lượng cho phép'
    //                 ], 500);
    //             }
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Thêm vào giỏ hàng thành công'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }


    public function create(Request $request)
    {
        try {

            $batchWithPromotion = Batch::where('product_id', $request->product_id)
                ->where('status', 'Expiring Soon')
                ->whereHas('batchPromotion', function ($query) {})
                ->whereHas('batchPromotion.promotion', function ($query) {
                    $query->where('status', 'active');
                })
                ->first();

            if (!$batchWithPromotion) {
                \Log::info('No batch with active promotion found after whereHas check.');
            }

            $isPromotionApplied = false;
            if ($batchWithPromotion && $batchWithPromotion->quantity > 0) {
                $isPromotionApplied = true;
            }

            $existsCart = Cart::where('user_id', $request->user_id)
                ->where('product_id', $request->product_id)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$existsCart) {
                $cart = new Cart();
                $cart->user_id = $request->user_id;
                $cart->product_id = $request->product_id;
                $cart->quantity = $request->quantity;
                $cart->total_weight = $request->total_weight;
                $cart->is_promotion_batch_applied = $isPromotionApplied;
                $cart->save();
            } else {
                if ($existsCart->is_promotion_batch_applied) {
                    if ($batchWithPromotion && ($existsCart->quantity + $request->quantity) <= $batchWithPromotion->quantity) {
                        // Cập nhật giỏ hàng hiện tại nếu còn đủ số lượng khuyến mãi
                        Cart::where('cart_id', $existsCart['cart_id'])->update([
                            'quantity' => $existsCart['quantity'] + $request->quantity,
                            'total_weight' => $existsCart['total_weight'] + $request->total_weight,
                            'is_promotion_batch_applied' => true
                        ]);
                    } else {
                        // Tạo giỏ hàng mới nếu không còn đủ số lượng khuyến mãi
                        $newCart = new Cart();
                        $newCart->user_id = $request->user_id;
                        $newCart->product_id = $request->product_id;
                        $newCart->quantity = $request->quantity;
                        $newCart->total_weight = $request->total_weight;
                        $newCart->is_promotion_batch_applied = false;
                        $newCart->save();
                    }
                } else {
                    // Cập nhật giỏ hàng hiện tại nếu không có gán nhãn khuyến mãi
                    Cart::where('cart_id', $existsCart['cart_id'])->update([
                        'quantity' => $existsCart['quantity'] + $request->quantity,
                        'total_weight' => $existsCart['total_weight'] + $request->total_weight,
                        'is_promotion_batch_applied' => false
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Thêm vào giỏ hàng thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function decrease($cart_id)
    {
        try {
            $cart = Cart::where("cart_id", $cart_id)->first();

            if ($cart['quantity'] > 1) {
                Cart::where("cart_id", $cart_id)->update([
                    'quantity' => $cart['quantity'] - 1,
                    'total_weight' => $cart['total_weight'] - $cart['total_weight'] / $cart['quantity']
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Giam san pham thanh cong'
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đã đến giới hạn giảm cho phép'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // public function increase($cart_id)
    // {
    //     try {
    //         $cart = Cart::where("cart_id", $cart_id)->first();

    //         if ($cart['quantity'] < 10) {
    //             Cart::where("cart_id", $cart_id)->update([
    //                 'quantity' => $cart['quantity'] + 1,
    //                 'total_weight' => $cart['total_weight'] + $cart['total_weight'] / $cart['quantity']
    //             ]);
    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'Tăng số lượng thành công'
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Đã đến giới hạn tăng cho phép'
    //             ], 500);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    //..///////Version in progress
    // public function increase($cart_id)
    // {
    //     try {
    //         $cart = Cart::where("cart_id", $cart_id)->first();

    //         if (!$cart) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Giỏ hàng không tồn tại'
    //             ], 404);
    //         }

    //         // Tạo một thể hiện của BatchController
    //         $batchController = new BatchController();
    //         $productStockResponse = $batchController->checkProductInStock($cart->product_id);
    //         $productStockData = json_decode($productStockResponse->getContent(), true);

    //         if ($productStockData['status'] === 'success' && $productStockData['data']) {
    //             $available_quantity = $productStockData['available_quantity'];

    //             if ($cart['quantity'] < $available_quantity) {
    //                 Cart::where("cart_id", $cart_id)->update([
    //                     'quantity' => $cart['quantity'] + 1,
    //                     'total_weight' => $cart['total_weight'] + $cart['total_weight'] / $cart['quantity']
    //                 ]);
    //                 return response()->json([
    //                     'status' => 'success',
    //                     'message' => 'Tăng số lượng thành công'
    //                 ], 200);
    //             } else {
    //                 return response()->json([
    //                     'status' => 'error',
    //                     'message' => 'Số lượng sản phẩm trong kho không đủ'
    //                 ], 400);
    //             }
    //         } elseif ($productStockData['status'] === 'out_of_stock') {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Sản phẩm đã hết hàng'
    //             ], 400);
    //         } else {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Không thể kiểm tra kho'
    //             ], 500);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }


    //          ////////////////////////////

    public function increase($cart_id)
    {
        try {
            $cart = Cart::where("cart_id", $cart_id)->first();

            if (!$cart) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Giỏ hàng không tồn tại'
                ], 404);
            }

            // Kiểm tra xem sản phẩm có gán nhãn khuyến mãi hay không
            if ($cart->is_promotion_batch_applied) {
                // Kiểm tra số lượng còn lại trong lô hàng áp dụng khuyến mãi
                $batchWithPromotion = Batch::where('product_id', $cart->product_id)
                    ->where('status', 'Expiring Soon')
                    ->whereHas('batchPromotion', function ($query) {})
                    ->whereHas('batchPromotion.promotion', function ($query) {
                        $query->where('status', 'active');
                    })
                    ->first();

                if ($batchWithPromotion && $batchWithPromotion->quantity > 0) {
                    if ($cart->quantity + 1 <= $batchWithPromotion->quantity) {
                        // Tăng số lượng trong giỏ hàng hiện tại nếu đủ số lượng khuyến mãi
                        Cart::where("cart_id", $cart_id)->update([
                            'quantity' => $cart->quantity + 1,
                            'total_weight' => $cart->total_weight + ($cart->total_weight / $cart->quantity),
                            'is_promotion_batch_applied' => true
                        ]);

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Tăng số lượng thành công'
                        ], 200);
                    } else {
                        // Tạo giỏ hàng mới không áp dụng khuyến mãi nếu số lượng khuyến mãi không đủ
                        $newCart = new Cart();
                        $newCart->user_id = $cart->user_id;
                        $newCart->product_id = $cart->product_id;
                        $newCart->quantity = 1; // Tăng thêm 1 sản phẩm
                        $newCart->total_weight = $cart->total_weight / $cart->quantity; // Tính trọng lượng cho 1 sản phẩm
                        $newCart->is_promotion_batch_applied = false;
                        $newCart->save();

                        return response()->json([
                            'status' => 'success',
                            'success' => 'Create new cart',
                            'message' => 'Tăng số lượng sản phẩm nhưng không áp dụng khuyến mãi'
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Lô hàng khuyến mãi đã hết hàng'
                    ], 400);
                }
            } else {
                // Tăng số lượng sản phẩm không áp dụng khuyến mãi
                Cart::where("cart_id", $cart_id)->update([
                    'quantity' => $cart->quantity + 1,
                    'total_weight' => $cart->total_weight + ($cart->total_weight / $cart->quantity),
                    'is_promotion_batch_applied' => false
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Tăng số lượng thành công'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function delete($cart_id)
    {
        try {
            Cart::where('cart_id', $cart_id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Xoá sản phẩm trong giỏ hàng thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function count()
    {
        try {
            $user_id = auth()->user()->id;
            $number = Cart::where('user_id', $user_id)->count();
            return response()->json([
                'status' => 'success',
                'number' => $number
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete_by_user_id()
    {
        try {
            $user_id = auth()->user()->id;
            Cart::where('user_id', $user_id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Delete All successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // public function update($product_id)
    // {
    //     try {
    //         $user_id = auth()->user()->id;
    //         $cartExist = Cart::where('user_id', $user_id)->where('product_id', $product_id)->orderBy('created_at', 'desc')->first();
    //         if($cartExist->quantity < 10){
    //             $existsCart = Cart::where('user_id', $user_id)->where('product_id', $request->product_id)->first();
    //         if (!$existsCart) {
    //             $cart = new Cart();
    //             $cart->user_id = $user_id;
    //             $cart->product_id = $product_id;
    //             $cart->quantity = $request->quantity;
    //             $cart->save();
    //         } else {
    //             if ($existsCart['quantity'] < 10) {
    //                 Cart::where('cart_id', $existsCart['cart_id'])->update([
    //                     'quantity' => $existsCart['quantity'] + 1
    //                 ]);
    //             } else {
    //                 return response()->json([
    //                     'status' => 'error',
    //                     'message' => 'Qúa số lượng cho phép'
    //                 ], 500);
    //             }
    //         }


    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Thêm vào giỏ hàng thành công'
    //         ], 200);
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Update successfully',
    //             'data' => $cartExist
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}
