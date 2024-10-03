<?php

namespace App\Http\Controllers\API\Statistic;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatisticController extends Controller
{

    public function filterUser(Request $request)
    {
        try {
            if (isset($request->start_date) && isset($request->end_date)) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $users = User::role(['normal_user', 'loyal_customer', 'affiliate_marketer'])
                    ->whereBetween('created_at', [$start_date, $end_date])->withCount(['order' => function ($query) {
                        $query->where('status', 'delivered');
                    }])
                    ->withSum(['order' => function ($query) {
                        $query->where('status', 'delivered');
                    }], 'total_cost')
                    ->get();
                return response()->json([
                    'status' => 'success',
                    'data' => $users
                ], 200);
            } else {
                $users = User::role(['normal_user', 'loyal_customer', 'affiliate_marketer'])
                    ->withCount(['order' => function ($query) {
                        $query->where('status', 'delivered');
                    }])
                    ->withSum(['order' => function ($query) {
                        $query->where('status', 'delivered');
                    }], 'total_cost')
                    ->get();
                return response()->json([
                    'status' => 'success',
                    'data' => $users
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function statisticProduct(Request $request)
    {
        try {
            if (isset($request->start_date) && isset($request->end_date)) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $products = Product::select('products.*')
                    ->withSum(['order_detail as total_cost_detail' => function ($query) use ($start_date, $end_date) {
                        $query->whereHas('order', function ($q) {
                            $q->where('status', 'delivered');
                        })
                            ->whereBetween('order_detail.created_at', [$start_date, $end_date]);
                    }], 'total_cost_detail')
                    ->withSum(['order_detail as total_quantity' => function ($query) use ($start_date, $end_date) {
                        $query->whereHas('order', function ($q) {
                            $q->where('status', 'delivered');
                        })
                            ->whereBetween('order_detail.created_at', [$start_date, $end_date]);
                    }], 'quantity')
                    ->get()
                    ->map(function ($product) {
                        return [
                            'product_id' => $product->product_id,
                            'product_name' => $product->product_name,
                            'product_img' => $product->product_img,
                            'product_des' => $product->product_des,
                            'category_id' => $product->category_id,
                            'product_price' => $product->product_price,
                            'weight' => $product->weight,
                            'product_views' => $product->product_views,
                            'total_cost_detail' => $product->total_cost_detail ?? 0,
                            'total_quantity' => $product->total_quantity ?? 0,
                            'created_at' => $product->created_at,
                            'updated_at' => $product->updated_at,
                        ];
                    });
                return response()->json([
                    'status' => 'successx',
                    'data' => $products
                ], 200);
            } else {
                $products = Product::select('products.*')
                    ->withSum(['order_detail as total_cost_detail' => function ($query) {
                        $query->whereHas('order', function ($q) {
                            $q->where('status', 'delivered');
                        });
                    }], 'total_cost_detail')
                    ->withSum(['order_detail as total_quantity' => function ($query) {
                        $query->whereHas('order', function ($q) {
                            $q->where('status', 'delivered');
                        });
                    }], 'quantity')
                    ->get()
                    ->map(function ($product) {
                        return [
                            'product_id' => $product->product_id,
                            'product_name' => $product->product_name,
                            'product_img' => $product->product_img,
                            'product_des' => $product->product_des,
                            'category_id' => $product->category_id,
                            'product_price' => $product->product_price,
                            'weight' => $product->weight,
                            'product_views' => $product->product_views,
                            'total_cost_detail' => $product->total_cost_detail ?? 0,
                            'total_quantity' => $product->total_quantity ?? 0,
                            'created_at' => $product->created_at,
                            'updated_at' => $product->updated_at,
                        ];
                    });
                return response()->json([
                    'status' => 'success',
                    'data' => $products
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function statisticOrder(Request $request)
    {
        try {
            if (isset($request->start_date) && isset($request->end_date)) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $orders = Order::whereBetween('created_at', [$start_date, $end_date])->where('status', 'delivered')->get();
                return response()->json([
                    'status' => 'success',
                    'data' => $orders
                ], 200);
            } else {
                $orders = Order::where('status', 'delivered')->get();
                return response()->json([
                    'status' => 'success',
                    'data' => $orders
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function monthlyRevenue(Request $request)
    {
        try {
            $year = $request->year ?? date('Y');

            $start_date = "{$year}-01-01";
            $end_date = "{$year}-12-31";

            $orders = Order::whereBetween('created_at', [$start_date, $end_date])
                ->where('status', 'delivered')
                ->get();

            // Khởi tạo mảng để lưu doanh thu hàng tháng
            $monthlyRevenue = array_fill(1, 12, 0);

            // Tính tổng doanh thu cho mỗi tháng
            foreach ($orders as $order) {
                $month = $order->created_at->format('n'); // 'n' trả về số tháng không có số 0 ở đầu
                $monthlyRevenue[$month] += $order->total_cost - $order->shipping_fee;
            }

            $monthlyStats = [];
            foreach ($monthlyRevenue as $month => $revenue) {
                $monthlyStats[] = [
                    'month' => sprintf('%d-%02d', $year, $month), // Định dạng: YYYY-MM
                    'revenue' => $revenue
                ];
            }

            return response()->json([
                'status' => 'success',
                'year' => $year,
                'data' => $monthlyStats
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function statisticUserByProvince(Request $request)
    {
        try {
            $year = $request->year ?? date('Y');
            $start_date = "{$year}-01-01";
            $end_date = "{$year}-12-31";

            $orders = Order::whereBetween('created_at', [$start_date, $end_date])
                ->where('status', 'delivered')
                ->get();

            $orders = $orders->map(callback: function ($order) {
                $orderData = $order->toArray();
                $orderAddress = json_decode($order->order_address, true);
                $orderData['city'] = $orderAddress['city'] ?? null;
                return $orderData;
            });

            $userProvince = [];
            foreach ($orders as $order) {
                if (!isset($userProvince[$order['city']])) {
                    $userProvince[$order['city']] = 0;
                }
                $userProvince[$order['city']] += 1;
            }

            return response()->json([
                'status' => 'success',
                'data' => $userProvince
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function profitByMonth(Request $request)
    {
        try {
            // Lấy năm từ request, nếu không có thì lấy năm hiện tại
            $year = $request->year ?? date('Y');

            $monthlyProfit = [];

            // Duyệt qua từng tháng
            for ($month = 1; $month <= 12; $month++) {
                // Tính ngày bắt đầu và kết thúc của tháng
                $monthStartDate = date('Y-m-d', strtotime("$year-$month-01"));
                $monthEndDate = date('Y-m-t', strtotime("$year-$month-01"));

                // Tính tổng doanh thu trong tháng (loại bỏ phí ship)
                $totalRevenue = Order::whereBetween('created_at', [$monthStartDate, $monthEndDate])
                    ->where('status', 'delivered')
                    ->sum(\DB::raw('total_cost - shipping_fee')); // Loại bỏ phí ship

                $orderDetails = \DB::table('order_detail')
                    ->join('orders', 'order_detail.order_id', '=', 'orders.order_id')
                    ->join('order_detail_batches', 'order_detail.order_detail_id', '=', 'order_detail_batches.order_detail_id')
                    ->join('batches', 'order_detail_batches.batch_id', '=', 'batches.batch_id')
                    ->where('orders.status', 'delivered')
                    ->whereBetween('order_detail.created_at', [$monthStartDate, $monthEndDate])
                    ->select(
                        'order_detail.quantity',
                        'order_detail.total_cost_detail',
                        'batches.batch_cost',
                        'batches.quantity as batch_quantity',
                        'batches.sold_quantity',
                        'order_detail_batches.quantity as batch_quantity_taken' // Số lượng lấy từ lô
                    )
                    ->get();

                // Tính tổng giá vốn
                $totalCost = 0;
                foreach ($orderDetails as $detail) {
                    // Tính giá trung bình của mỗi sản phẩm trong lô hàng
                    $productCostPerBatch = $detail->batch_cost / ($detail->batch_quantity + $detail->sold_quantity);

                    // Tính giá vốn cho số lượng sản phẩm đã bán từ lô hàng cụ thể
                    $totalCost += $productCostPerBatch * $detail->batch_quantity_taken;
                }


                // Lợi nhuận = Doanh thu - Giá vốn
                $monthlyProfit[$month] = $totalRevenue - $totalCost;
            }

            // Trả về dữ liệu lợi nhuận từng tháng
            return response()->json([
                'status' => 'success',
                'data' => $monthlyProfit
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function totalSalesByMonth(Request $request)
    {
        try {
            // Lấy năm và tháng từ request. Nếu không có, sử dụng năm và tháng hiện tại.
            $year = $request->year ?? date('Y');
            $month = $request->month ?? date('m');

            // Tính ngày bắt đầu và kết thúc của tháng
            $monthStartDate = date('Y-m-d', strtotime("$year-$month-01"));
            $monthEndDate = date('Y-m-t', strtotime("$year-$month-01"));

            // Tính tổng doanh thu trong tháng (loại bỏ phí ship)
            $totalRevenue = Order::whereBetween('created_at', [$monthStartDate, $monthEndDate])
                ->where('status', 'delivered') // Chỉ tính đơn hàng đã giao
                ->sum(\DB::raw('total_cost - shipping_fee')); // Loại bỏ phí ship

            // Trả về kết quả dưới dạng JSON
            return response()->json([
                'status' => 'success',
                'total_sales' => $totalRevenue,
                'month' => $month,
                'year' => $year
            ], 200);
        } catch (\Exception $e) {
            // Xử lý lỗi
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function totalSalesByYear(Request $request)
    {
        try {
            // Lấy năm từ request, nếu không có thì sử dụng năm hiện tại
            $year = $request->year ?? date('Y');

            // Tính ngày bắt đầu và kết thúc của năm
            $yearStartDate = "{$year}-01-01";
            $yearEndDate = "{$year}-12-31";

            // Tính tổng doanh thu trong năm (loại bỏ phí ship)
            $totalRevenue = Order::whereBetween('created_at', [$yearStartDate, $yearEndDate])
                ->where('status', 'delivered') // Chỉ tính đơn hàng đã giao
                ->sum(\DB::raw('total_cost - shipping_fee')); // Loại bỏ phí ship

            // Trả về kết quả dưới dạng JSON
            return response()->json([
                'status' => 'success',
                'total_sales' => $totalRevenue,
                'year' => $year
            ], 200);
        } catch (\Exception $e) {
            // Xử lý lỗi
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function totalProfitByMonth(Request $request)
    {
        try {
            // Lấy năm và tháng từ request. Nếu không có, sử dụng năm và tháng hiện tại.
            $year = $request->year ?? date('Y');
            $month = $request->month ?? date('m');

            // Tính ngày bắt đầu và kết thúc của tháng
            $monthStartDate = date('Y-m-d', strtotime("$year-$month-01"));
            $monthEndDate = date('Y-m-t', strtotime("$year-$month-01"));

            // Tính tổng doanh thu trong tháng (loại bỏ phí ship)
            $totalRevenue = Order::whereBetween('created_at', [$monthStartDate, $monthEndDate])
                ->where('status', 'delivered')
                ->sum(\DB::raw('total_cost - shipping_fee')); // Loại bỏ phí ship

            // Lấy chi tiết đơn hàng và lô hàng trong tháng
            $orderDetails = \DB::table('order_detail')
                ->join('orders', 'order_detail.order_id', '=', 'orders.order_id')
                ->join('order_detail_batches', 'order_detail.order_detail_id', '=', 'order_detail_batches.order_detail_id')
                ->join('batches', 'order_detail_batches.batch_id', '=', 'batches.batch_id')
                ->where('orders.status', 'delivered') // Chỉ tính đơn hàng đã giao
                ->whereBetween('order_detail.created_at', [$monthStartDate, $monthEndDate])
                ->select(
                    'order_detail.quantity',
                    'order_detail.total_cost_detail',
                    'batches.batch_cost',
                    'batches.quantity as batch_quantity',
                    'batches.sold_quantity',
                    'order_detail_batches.quantity as batch_quantity_taken'
                )
                ->get();

            // Tính tổng giá vốn
            $totalCost = 0;
            foreach ($orderDetails as $detail) {
                // Tính giá trung bình của mỗi sản phẩm trong lô hàng
                $productCostPerBatch = $detail->batch_cost / ($detail->batch_quantity + $detail->sold_quantity);

                // Tính giá vốn cho số lượng sản phẩm đã bán từ lô hàng cụ thể
                $totalCost += $productCostPerBatch * $detail->batch_quantity_taken;
            }

            // Tính tổng lợi nhuận = Doanh thu - Giá vốn
            $totalProfit = $totalRevenue - $totalCost;

            // Trả về kết quả dưới dạng JSON
            return response()->json([
                'status' => 'success',
                'total_profit' => $totalProfit,
                'month' => $month,
                'year' => $year
            ], 200);
        } catch (\Exception $e) {
            // Xử lý lỗi
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function totalProfitByYear(Request $request)
    {
        try {
            // Lấy năm từ request, nếu không có thì sử dụng năm hiện tại
            $year = $request->year ?? date('Y');

            // Tính ngày bắt đầu và kết thúc của năm
            $yearStartDate = "{$year}-01-01";
            $yearEndDate = "{$year}-12-31";

            // Tính tổng doanh thu trong năm (loại bỏ phí ship)
            $totalRevenue = Order::whereBetween('created_at', [$yearStartDate, $yearEndDate])
                ->where('status', 'delivered')
                ->sum(\DB::raw('total_cost - shipping_fee')); // Loại bỏ phí ship

            // Lấy chi tiết đơn hàng và lô hàng trong năm
            $orderDetails = \DB::table('order_detail')
                ->join('orders', 'order_detail.order_id', '=', 'orders.order_id')
                ->join('order_detail_batches', 'order_detail.order_detail_id', '=', 'order_detail_batches.order_detail_id')
                ->join('batches', 'order_detail_batches.batch_id', '=', 'batches.batch_id')
                ->where('orders.status', 'delivered') // Chỉ tính đơn hàng đã giao
                ->whereBetween('order_detail.created_at', [$yearStartDate, $yearEndDate])
                ->select(
                    'order_detail.quantity',
                    'order_detail.total_cost_detail',
                    'batches.batch_cost',
                    'batches.quantity as batch_quantity',
                    'batches.sold_quantity',
                    'order_detail_batches.quantity as batch_quantity_taken'
                )
                ->get();

            // Tính tổng giá vốn
            $totalCost = 0;
            foreach ($orderDetails as $detail) {
                // Tính giá trung bình của mỗi sản phẩm trong lô hàng
                $productCostPerBatch = $detail->batch_cost / ($detail->batch_quantity + $detail->sold_quantity);

                // Tính giá vốn cho số lượng sản phẩm đã bán từ lô hàng cụ thể
                $totalCost += $productCostPerBatch * $detail->batch_quantity_taken;
            }

            // Tính tổng lợi nhuận = Doanh thu - Giá vốn
            $totalProfit = $totalRevenue - $totalCost;

            // Trả về kết quả dưới dạng JSON
            return response()->json([
                'status' => 'success',
                'total_profit' => $totalProfit,
                'year' => $year
            ], 200);
        } catch (\Exception $e) {
            // Xử lý lỗi
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
