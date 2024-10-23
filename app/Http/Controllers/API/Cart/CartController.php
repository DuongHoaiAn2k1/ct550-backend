<?php

namespace App\Http\Controllers\API\Cart;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
                ->with('product', 'product.product_promotion', 'product.product_promotion.promotion')
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


    public function create(Request $request)
    {
        // return response()->json([
        //     "data" => $request->all()
        // ], 202);
        try {
            $existsCart = Cart::where('user_id', $request->user_id)->where('product_id', $request->product_id)->orderBy('created_at', 'desc')->first();
            if (!$existsCart) {
                $cart = new Cart();
                $cart->user_id = $request->user_id;
                $cart->product_id = $request->product_id;
                $cart->quantity = $request->quantity;
                $cart->total_weight = $request->total_weight;
                $cart->save();
            } else {
                if ($existsCart['quantity'] + $request->quantity <= 10) {
                    Cart::where('cart_id', $existsCart['cart_id'])->update([
                        'quantity' => $existsCart['quantity'] + $request->quantity,
                        'total_weight' => $existsCart['total_weight'] + $request->total_weight
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Qúa số lượng cho phép'
                    ], 500);
                    // $cart = new Cart();
                    // $cart->user_id = $request->user_id;
                    // $cart->product_id = $request->product_id;
                    // $cart->quantity = $request->quantity;
                    // $cart->save();
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
            ]);
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

    public function increase($cart_id)
    {
        try {
            $cart = Cart::where("cart_id", $cart_id)->first();

            if ($cart['quantity'] < 10) {
                Cart::where("cart_id", $cart_id)->update([
                    'quantity' => $cart['quantity'] + 1,
                    'total_weight' => $cart['total_weight'] + $cart['total_weight'] / $cart['quantity']
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tăng số lượng thành công'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đã đến giới hạn tăng cho phép'
                ], 500);
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
