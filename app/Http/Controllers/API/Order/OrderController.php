<?php

namespace App\Http\Controllers\API\Order;

use App\Models\User;
use App\Models\Order;
use App\Models\Commission;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\AffiliateSale;
use App\Mail\OrderCreatedMail;
use App\Models\AffiliateWallet;
use App\Events\Order\OrderCreated;
use Illuminate\Support\Facades\DB;
use App\Events\Order\OrderCancelled;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Events\Order\OrderUpdateStatus;
use Illuminate\Support\Facades\Validator;
use App\Events\Order\PaymentSetPrepareStatus;
use App\Http\Controllers\API\Notification\NotificationController;

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

    public function getByBillId($bill_id)
    {
        try {
            $order = Order::with('orderDetail.product')->where('bill_id', $bill_id)->first();
            return response()->json([
                'status' => 'success',
                'message' => 'Get Order SuccessFully',
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
                'paid.required' =>  'Trạng thái thanh toán không được để trống',
                'shipping_fee.required' => "Phí ship không được để trống",
                'total_cost.required' => 'Tổng giá tiền không được để trống'
            ];

            $validate = Validator::make($request->all(), [
                'bill_id' => 'required',
                'status' => 'required',
                'paid' => 'required',
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
                if (isset($request->pay_method) && $request->pay_method == "cod") {
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
                    $order->paid = $request->paid;
                    $order->shipping_fee = $request->shipping_fee;
                    $order->total_cost = $request->total_cost;
                    $order->point_used_order = $request->point_used_order;
                    $order->save();
                    $order_id = $order->order_id;
                    if ($request->status == 'preparing') {
                        $notificationController = new NotificationController();
                        $notificationRequest = new Request([
                            'message' => 'Đơn hàng đã đặt thành công',
                            'route_name' => 'order',
                            'type' => 'admin'
                        ]);

                        $notificationController->create($notificationRequest);
                    }
                    event(new OrderCreated($order->user_id));
                    return response()->json([
                        'status' => 'success',
                        'data' => $request->all(),
                        'order_id' => $order_id,
                        'message' => 'Tạo đơn hàng thành công'
                    ], 201);
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

                    $order->paid = 0;
                    $order->pay_method = $request->pay_method;
                    $order->shipping_fee = $request->shipping_fee;
                    $order->total_cost = $request->total_cost;
                    $order->point_used_order = $request->point_used_order;
                    $order->save();
                    $order_id = $order->order_id;
                    event(new OrderCreated($order->user_id));
                    return response()->json([
                        'status' => 'success',
                        'data' => $request->all(),
                        'order_id' => $order_id,
                        'message' => 'Tạo đơn hàng thành công'
                    ], 201);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function sendOrderConfirmationEmail($order_id)
    {
        try {
            $order = Order::with('orderDetail.product')->where('order_id', $order_id)->first();

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đơn hàng không tồn tại'
                ], 404);
            }

            Mail::to(auth()->user()->email)->send(new OrderCreatedMail($order));

            return response()->json([
                'status' => 'success',
                'message' => 'Email xác nhận đơn hàng đã được gửi'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi gửi email: ' . $e->getMessage()
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
            $order->status = 'cancelled';
            $order->save();
            event(new OrderCancelled());
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
            $affiliateSale = AffiliateSale::where('order_id', $order_id)->first() ?? null;
            if ($affiliateSale) {
                $affiliateWallet = AffiliateWallet::where('affiliate_user_id', $affiliateSale->affiliate_user_id)->first();
            }
            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đơn hàng không tồn tại'
                ], 404);
            }

            $order->status = $request->status;
            if ($order->total_cost >= 1000000 && $request->status == 'delivered') {
                $user = User::where('id', $order->user_id)->first();
                $user->point = $user->point + 10;
                $user->save();
            }

            if ($affiliateSale) {
                if ($request->status == 'delivered') {
                    $affiliateSale->order_status = 'done';
                    $affiliateSale->save();
                    $affiliateWallet->balance += $affiliateSale->commission_amount;
                    $affiliateWallet->save();
                } else {
                    $affiliateSale->order_status = 'pending';
                    $affiliateSale->save();
                }

                if ($request->status == 'cancelled') {
                    $affiliateSale->delete();
                }
            }
            $order->save();
            event(new OrderUpdateStatus($order->user_id));
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

    public function updateStatusByBillId(Request $request, $bill_id)
    {
        try {
            $order = Order::where('bill_id', $bill_id)->first();
            $lastStatus = $order->status;
            $order->status = $request->status;
            $order->paid = 1;
            $order->save();
            if ($request->status == 'preparing' && $lastStatus != 'preparing') {
                $notificationController = new NotificationController();
                $notificationRequest = new Request([
                    'message' => 'Đơn hàng đã đặt thành công',
                    'route_name' => 'order',
                    'type' => 'admin'
                ]);
                $notificationController->create($notificationRequest);
                $this->sendOrderConfirmationEmail($order->order_id);
            }
            // event(new OrderCreated());
            // event(new OrderUpdateStatus());
            event(new PaymentSetPrepareStatus());
            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật trạng thái đơn hàng thành công'
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
}
