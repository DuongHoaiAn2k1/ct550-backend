<?php

namespace App\Http\Controllers\API\Order;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Events\Order\OrderCreated;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function get($order_id)
    {
        try {
            $order = Order::with('orderDetail.product')->where('order_id', $order_id)->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy đơn hàng thành công',
                'data' => $order
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
            $list_order = Order::with('orderDetail.product')->where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Get List Order SuccessFully',
                'data' => $list_order
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get_by_user_id($user_id)
    {
        try {
            $list_order = Order::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Get List Order Successfully',
                'data' => $list_order
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
            $orders = Order::with('orderDetail.product')->orderBy('created_at', 'desc')->get();
            $dataLength = count($orders);
            return response()->json([
                'message' => 'success',
                'data' => $orders,
                'length' => $dataLength
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
        try {
            $customMessage = [
                'bill_id.required' => 'Mã đơn hàng không được để trống.',
                'status.required' => 'Mã trạng thái không được để trống.',
                // 'paid.required' =>  'Trạng thái thanh toán không được để trống',
                'shipping_fee.required' => "Phí ship không được để trống",
                'total_cost.required' => 'Tổng giá tiền không được để trống'
            ];

            $validate = Validator::make($request->all(), [
                'bill_id' => 'required',
                'status' => 'required',
                // 'paid' => 'required',
                'shipping_fee' => 'required',
                'total_cost' => 'required'
            ], $customMessage);

            if ($validate->fails()) {
                $errors = $validate->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ], 422);
            } else {
                $order = new Order();
                $order->bill_id = $request->bill_id;
                $order->status = $request->status;
                $order->user_id = auth()->user()->id;
                $order_address = [
                    'address' => $request->address,
                    'commue' => $request->commue,
                    'district' => $request->district,
                    'city' => $request->city,
                    'phone' => $request->phone,
                    'name' => $request->name
                ];
                $order->order_address = json_encode($order_address);

                // $order->paid = $request->paid;
                $order->shipping_fee = $request->shipping_fee;
                $order->total_cost = $request->total_cost;
                $order->point_used_order = $request->point_used_order;
                $order->save();
                $order_id = $order->order_id;
                event(new OrderCreated());
                return response()->json([
                    'status' => 'success',
                    'data' => $request->all(),
                    'order_id' => $order_id,
                    'message' => 'Tạo đơn hàng thành công'
                ], 201);
            }
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
            Order::where('order_id', $id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Hủy đơn hàng thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function count()
    {
        try {
            $user_id = auth()->user()->id;
            $total = Order::where('user_id', $user_id)->count();
            return response()->json([
                'status' => 'success',
                'total' => $total
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function cancel($order_id)
    {
        try {
            $order = Order::where('order_id', $order_id)->first();

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đơn hàng không tồn tại'
                ], 404);
            }

            $order->status = 0;
            $order->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Hủy đơn hàng thành công',
                'data' => $order
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update_status(Request $request, $order_id)
    {
        try {
            $order = Order::where('order_id', $order_id)->first();

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đơn hàng không tồn tại'
                ], 404);
            }

            $order->status = $request->status;
            $order->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật trạng thái đơn hàng thành công',
                'data' => $order
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get_order_today()
    {
        try {
            $today = now()->format('Y-m-d');
            $order_today = Order::with('orderDetail.product')
                ->whereDate('created_at', $today)
                ->orderBy('created_at', 'desc')
                ->get();
            $data_length = count($order_today);

            return response()->json([
                'status' => 'success',
                'message' => 'Get List Order for Today Successfully',
                'data' => $order_today,
                'length' => $data_length
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get_orders_between_dates(Request $request)
    {
        try {
            // Validate request data
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

            // Query orders between start date and end date
            $orders = Order::with('orderDetail.product')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->orderBy('created_at', 'desc')
                ->get();

            $data_length = count($orders);

            return response()->json([
                'status' => 'success',
                'message' => 'Get List Order between dates successfully',
                'data' => $orders,
                'length' => $data_length
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function list_user_order()
    {
        try {

            $usersOrders = Order::select('users.id', 'users.name', DB::raw('COUNT(*) as total_orders'), DB::raw('SUM(total_cost) as total_cost'))
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->groupBy('users.id', 'users.name')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'List of Users with Total Orders and Total Cost',
                'data' => $usersOrders
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getOrderByCondition(Request $request)
    {
        try {
            $condition = $request->condition;
            $query = Order::query();

            switch ($condition) {
                case 1:
                    // Lấy danh sách tất cả các đơn hàng cùng với tổng số sản phẩm trong mỗi đơn hàng
                    $orders = $query->withCount('orderDetail')->get();
                    $cancel_count = count($orders->where('status', 0));
                    $prepare_count = count($orders->where('status', 1));
                    $shipping_count = count($orders->where('status', 2));
                    $receive_count = count($orders->where('status', 3));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Lấy danh sách tất cả các đơn hàng cùng với tổng số sản phẩm trong mỗi đơn hàng thành công',
                        'data' => $orders,
                        'cancel_number' => $cancel_count,
                        'prepare_number' => $prepare_count,
                        'shipping_number' => $shipping_count,
                        'receive_number' => $receive_count
                    ], 200);
                    break;
                case 2:
                    // Lấy danh sách đơn hàng được đặt trong tháng này
                    $currentMonth = date('m');
                    $currentYear = date('Y');

                    $orders = $query->whereMonth('created_at', '=', $currentMonth)
                        ->whereYear('created_at', '=', $currentYear)
                        ->orderBy('created_at', 'desc')
                        ->get();
                    $cancel_count = count($orders->where('status', 0));
                    $prepare_count = count($orders->where('status', 1));
                    $shipping_count = count($orders->where('status', 2));
                    $receive_count = count($orders->where('status', 3));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Lấy danh sách đơn hàng được đặt trong tháng này thành công',
                        'data' => $orders,
                        'cancel_number' => $cancel_count,
                        'prepare_number' => $prepare_count,
                        'shipping_number' => $shipping_count,
                        'receive_number' => $receive_count
                    ], 200);
                    break;
                case 3:
                    // Lấy thời gian bắt đầu và kết thúc của tháng trước
                    $currentMonth = date('m');
                    $currentYear = date('Y');

                    if ($currentMonth == 1) {
                        $lastMonth = 12;
                        $year = $currentYear - 1;
                    } else {
                        $lastMonth = $currentMonth - 1;
                        $year = $currentYear;
                    }

                    // Xác định ngày đầu tiên của tháng trước
                    $startDate = date('Y-m-01', strtotime("$year-$lastMonth"));

                    // Xác định ngày cuối cùng của tháng trước
                    $endDate = date('Y-m-t', strtotime("$year-$lastMonth"));

                    // Thêm thời gian vào ngày cuối cùng
                    $endDate .= ' 23:59:59';

                    // Lấy danh sách đơn hàng được đặt trong tháng trước
                    $orders = $query->whereBetween('created_at', [$startDate, $endDate])
                        ->orderBy('created_at', 'desc')
                        ->get();

                    $cancel_count = count($orders->where('status', 0));
                    $prepare_count = count($orders->where('status', 1));
                    $shipping_count = count($orders->where('status', 2));
                    $receive_count = count($orders->where('status', 3));

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Lấy danh sách đơn hàng được đặt trong tháng trước thành công',
                        'data' => $orders,
                        'cancel_number' => $cancel_count,
                        'prepare_number' => $prepare_count,
                        'shipping_number' => $shipping_count,
                        'receive_number' => $receive_count
                    ], 200);
                    break;

                case 4:
                    // Lấy danh sách đơn hàng được đặt trong năm 2024
                    $orders = $query->whereYear('created_at', '=', 2024)
                        ->orderBy('created_at', 'desc')
                        ->get();
                    $cancel_count = count($orders->where('status', 0));
                    $prepare_count = count($orders->where('status', 1));
                    $shipping_count = count($orders->where('status', 2));
                    $receive_count = count($orders->where('status', 3));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Lấy danh sách đơn hàng được đặt trong năm 2024 thành công',
                        'data' => $orders,
                        'cancel_number' => $cancel_count,
                        'prepare_number' => $prepare_count,
                        'shipping_number' => $shipping_count,
                        'receive_number' => $receive_count
                    ], 200);
                    break;
                case 5:
                    // Lấy danh sách đơn hàng được đặt trong năm 2023
                    $orders = $query->whereYear('created_at', '=', 2023)
                        ->orderBy('created_at', 'desc')
                        ->get();
                    $cancel_count = count($orders->where('status', 0));
                    $prepare_count = count($orders->where('status', 1));
                    $shipping_count = count($orders->where('status', 2));
                    $receive_count = count($orders->where('status', 3));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Lấy danh sách đơn hàng được đặt trong năm 2023 thành công',
                        'data' => $orders,
                        'cancel_number' => $cancel_count,
                        'prepare_number' => $prepare_count,
                        'shipping_number' => $shipping_count,
                        'receive_number' => $receive_count
                    ], 200);
                    break;
                default:
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Condition không hợp lệ'
                    ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function calculateTotalCostAndShippingFee(Request $request)
    {
        try {
            $condition = $request->condition;
            $query = Order::where('status', '=', 3); // Chỉ tính toán trên các đơn hàng có status là 3
            $totalCost = 0;
            $shippingFee = 0;
            $startDate = null;
            $endDate = null;
            $currentMonth = null;
            $currentYear = null;
            $lastMonth = null;
            $year = null;
            switch ($condition) {
                case 1:
                    // Tính tổng của total_cost và shipping_fee cho tất cả các đơn hàng
                    $totalCost = $query->sum('total_cost');
                    $shippingFee = $query->sum('shipping_fee');
                    break;
                case 2:
                    // Tính tổng của total_cost và shipping_fee cho các đơn hàng được đặt trong tháng này
                    $currentMonth = date('m');
                    $currentYear = date('Y');

                    $totalCost = $query->whereMonth('created_at', '=', $currentMonth)
                        ->whereYear('created_at', '=', $currentYear)
                        ->sum('total_cost');

                    $shippingFee = $query->whereMonth('created_at', '=', $currentMonth)
                        ->whereYear('created_at', '=', $currentYear)
                        ->sum('shipping_fee');

                    $startDate = $currentYear . '-' . $currentMonth . '-01T00:00:00.000Z';
                    $endDate = date('Y-m-t', strtotime($startDate)) . 'T23:59:59.999Z';
                    break;
                case 3:
                    // Tính tổng của total_cost và shipping_fee cho các đơn hàng được đặt trong tháng trước
                    $currentMonth = date('m');
                    $currentYear = date('Y');

                    if ($currentMonth == "01") {
                        $lastMonth = 12;
                        $year = $currentYear - 1;
                    } else {
                        $lastMonth = $currentMonth - 1;
                        $year = $currentYear;
                    }

                    // Xác định ngày đầu tiên của tháng trước
                    $startDate = $year . '-' . str_pad($lastMonth, 2, '0', STR_PAD_LEFT) . '-01T00:00:00.000Z';

                    // Xác định ngày cuối cùng của tháng trước
                    $endDate = $year . '-' . str_pad($lastMonth, 2, '0', STR_PAD_LEFT) . '-' . date('t', strtotime("$year-$lastMonth")) . 'T23:59:59.999Z';

                    $totalCost = $query->whereBetween('created_at', [$startDate, $endDate])
                        ->sum('total_cost');

                    $shippingFee = $query->whereBetween('created_at', [$startDate, $endDate])
                        ->sum('shipping_fee');
                    break;
                case 4:
                    // Tính tổng của total_cost và shipping_fee cho các đơn hàng được đặt trong năm 2024
                    $totalCost = $query->whereYear('created_at', '=', 2024)
                        ->sum('total_cost');

                    $shippingFee = $query->whereYear('created_at', '=', 2024)
                        ->sum('shipping_fee');
                    break;
                case 5:
                    // Tính tổng của total_cost và shipping_fee cho các đơn hàng được đặt trong năm 2023
                    $totalCost = $query->whereYear('created_at', '=', 2023)
                        ->sum('total_cost');

                    $shippingFee = $query->whereYear('created_at', '=', 2023)
                        ->sum('shipping_fee');
                    break;
                default:
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Condition không hợp lệ'
                    ], 400);
            }

            return response()->json([
                'status' => 'success',
                'total_cost' => $totalCost,
                'shipping_fee' => $shippingFee,
                // 'start_date' => $startDate,
                // 'end_date' => $endDate,
                // 'current_month' => $currentMonth,
                // "current_year" => $currentYear,
                // "last_month" => $lastMonth
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
