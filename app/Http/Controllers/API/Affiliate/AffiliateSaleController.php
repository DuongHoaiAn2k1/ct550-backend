<?php

namespace App\Http\Controllers\API\Affiliate;

use App\Models\Order;
use App\Models\Product;
use App\Models\Commission;
use Illuminate\Http\Request;
use App\Models\AffiliateSale;
use App\Models\AffiliateWithdrawal;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AffiliateSaleController extends Controller
{
    // List all affiliate sales with pagination
    public function index()
    {
        $affiliateSales = AffiliateSale::with(['affiliateUser', 'product', 'order'])->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => $affiliateSales
        ]);
    }

    public function getListAffiliateOrderSale()
    {
        try {
            $affiliateSales = AffiliateSale::with(
                [
                    'affiliateUser',
                    'product',
                    'order' => function ($query) {
                        $query->where('status', 'delivered');
                    },
                    'order.orderDetail'

                ]
            )->get();

            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Get List Affiliate Sale SuccessFully',
                    'data' => $affiliateSales
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ],
                500
            );
        }
    }

    // Create a new affiliate sale
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'affiliate_user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'order_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 422);
        }

        $existsProductCommision = Commission::where('product_id', $request->product_id)->first();
        if (!$existsProductCommision) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sản phẩm chưa được tạo hoa hồng'
            ], 400);
        }

        try {
            $affiliateSale = new AffiliateSale();
            $commission = Commission::where('product_id', $request->product_id)->first();
            $product = Product::where('product_id', $request->product_id)->first();
            $affiliateSale->affiliate_user_id = $request->affiliate_user_id;
            $affiliateSale->product_id = $request->product_id;
            $affiliateSale->order_id = $request->order_id;
            $affiliateSale->commission_amount = round(($request->quantity * $product->product_price) * ($commission->commission_rate / 100));
            $affiliateSale->commission_rate = $commission->commission_rate;
            $affiliateSale->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Tạo hoa hồng thành công'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getByUser()
    {
        try {
            $affiliateSales = AffiliateSale::with(['affiliateUser', 'product', 'order', 'order.orderDetail'])->where('affiliate_user_id', auth()->user()->id)->where('order_status', 'done')->get();
            return response()->json([
                'status' => 'success',
                'data' => $affiliateSales
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function changOrderStatus($order_id)
    {
        try {
            $affiliateSale = AffiliateSale::where('order_id', request()->order_id)->first();
            $affiliateSale->order_status = 'done';
            $affiliateSale->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật trạng thái thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    function calculateAffiliateStatistics(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        // Tổng doanh thu từ chương trình tiếp thị
        $totalRevenue = AffiliateSale::whereHas('order', function ($query) use ($month, $year) {
            $query->where('status', 'delivered')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year);
        })
            ->get()
            ->sum(function ($affiliateSale) {
                $order = $affiliateSale->order;
                return $order->total_cost - $order->shipping_fee;
            });

        // Tổng hoa hồng của người tiếp thị liên kết
        $totalCommission = AffiliateSale::whereHas('order', function ($query) use ($month, $year) {
            $query->where('status', 'delivered')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year);
        })
            ->sum('commission_amount');

        // Tổng số tiền đã chuyển cho người tiếp thị
        $totalWithdrawals = AffiliateWithdrawal::where('status', 'done')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('amount');

        // Tổng số đơn hàng
        $totalOrders = AffiliateSale::whereHas('order', function ($query) use ($month, $year) {
            $query->where('status', 'delivered')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year);
        })
            ->distinct('order_id') // Đảm bảo không tính trùng lặp đơn hàng
            ->count('order_id');

        return [
            'total_revenue' => $totalRevenue,
            'total_commission' => $totalCommission,
            'total_withdrawals' => $totalWithdrawals,
            'total_orders' => $totalOrders
        ];
    }
}
