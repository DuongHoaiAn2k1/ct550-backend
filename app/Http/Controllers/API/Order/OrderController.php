<?php

namespace App\Http\Controllers\API\Order;

use App\Models\User;
use App\Models\Batch;
use App\Models\Order;
use App\Models\Commission;
use App\Models\OrderDetail;
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
            // Bắt đầu transaction
            DB::beginTransaction();

            // Custom message cho việc validate
            $customMessage = [
                'bill_id.required' => 'Mã đơn hàng không được để trống.',
                'status.required' => 'Mã trạng thái không được để trống.',
                'paid.required' =>  'Trạng thái thanh toán không được để trống',
                'shipping_fee.required' => "Phí ship không được để trống",
                'total_cost.required' => 'Tổng giá tiền không được để trống',
                'order_details.required' => 'Chi tiết đơn hàng không được để trống.',
            ];

            // Validate cho đơn hàng
            $validate = Validator::make($request->all(), [
                'bill_id' => 'required',
                'status' => 'required',
                'paid' => 'required',
                'shipping_fee' => 'required',
                'total_cost' => 'required',
                'order_details' => 'required|array', // Đảm bảo có chi tiết đơn hàng
            ], $customMessage);

            if ($validate->fails()) {
                $errors = $validate->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ], 422);
            }

            // Tạo đơn hàng
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
            $order->pay_method = $request->pay_method;
            $order->save();

            // Xử lý trừ stock trực tiếp
            foreach ($request->order_details as $orderDetailData) {
                $product_id = $orderDetailData['product_id'];
                $quantity_to_reduce = $orderDetailData['quantity'];

                $date_threshold = now()->addDays(15);

                // Lấy các batch còn stock của sản phẩm
                $batches = Batch::where('product_id', $product_id)
                    ->where('status', 'Active')
                    ->where('expiry_date', '>', $date_threshold)
                    ->orderBy('entry_date', 'asc')
                    ->lockForUpdate()
                    ->get();

                $batchDetails = [];

                foreach ($batches as $batch) {
                    if ($quantity_to_reduce <= 0) {
                        break;
                    }

                    if ($batch->quantity >= $quantity_to_reduce) {
                        $batch->quantity -= $quantity_to_reduce;
                        $batch->sold_quantity += $quantity_to_reduce;
                        $batch->save();

                        $batchDetails[] = [
                            'batch_id' => $batch->batch_id,
                            'quantity' => $quantity_to_reduce
                        ];
                        $quantity_to_reduce = 0;
                    } else {
                        $quantity_to_reduce -= $batch->quantity;

                        $batchDetails[] = [
                            'batch_id' => $batch->batch_id,
                            'quantity' => $batch->quantity
                        ];
                        $batch->sold_quantity += $batch->quantity;
                        $batch->quantity = 0;
                        $batch->save();
                    }
                }

                // Kiểm tra nếu không đủ stock
                if ($quantity_to_reduce > 0) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Not enough stock to fulfill the order.'
                    ], 400);
                }

                // Tạo chi tiết đơn hàng
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->order_id;
                $orderDetail->product_id = $orderDetailData['product_id'];
                $orderDetail->quantity = $orderDetailData['quantity'];
                $orderDetail->total_cost_detail = $orderDetailData['total_cost_detail'];
                $orderDetail->save();

                // Lưu các chi tiết batch
                foreach ($batchDetails as $batchDetail) {
                    DB::table('order_detail_batches')->insert([
                        'order_detail_id' => $orderDetail->order_detail_id,
                        'batch_id' => $batchDetail['batch_id'],
                        'quantity' => $batchDetail['quantity'],
                    ]);
                }
            }

            // Giảm điểm tích lũy của người dùng nếu có sử dụng điểm
            if ($order->point_used_order > 0) {
                $user = auth()->user();

                if ($user->point < $order->point_used_order) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'User does not have enough points.'
                    ], 400);
                }

                // Giảm số điểm của người dùng
                $user->point -= $order->point_used_order;
                $user->point_used += $order->point_used_order;
                $user->save();
            }

            // Hoàn thành transaction
            DB::commit();

            event(new OrderCreated($order->user_id));

            if ($request->status == 'preparing') {
                $notificationController = new NotificationController();
                $notificationRequest = new Request(query: [
                    'message' => 'Đơn hàng đã đặt thành công',
                    'route_name' => 'order',
                    'type' => 'admin'
                ]);
                $notificationController->create($notificationRequest);
            }

            return response()->json([
                'status' => 'success',
                'order_id' => $order->order_id,
                'message' => 'Tạo đơn hàng và chi tiết đơn hàng thành công'
            ], 201);
        } catch (\Exception $e) {
            // Rollback nếu có lỗi
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function sendOrderConfirmationEmail($order_id)
    {
        try {
            // Lấy thông tin đơn hàng với chi tiết sản phẩm và khuyến mãi
            $order = Order::with('orderDetail.product')
                ->with('orderDetail.product.product_promotion')
                ->with('orderDetail.product.product_promotion.promotion')
                ->where('order_id', $order_id)
                ->first();

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đơn hàng không tồn tại'
                ], 404);
            }

            // Lấy user_id từ order để lấy thông tin người dùng
            $user = User::find($order->user_id);
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Người dùng không tồn tại'
                ], 404);
            }

            // Lấy danh sách vai trò của người dùng
            $roles = $user->getRoleNames();

            // Xác định vai trò chính của người dùng (ngoại trừ vai trò 'affiliate_marketer')
            $mainRole = $roles->filter(function ($role) {
                return $role !== 'affiliate_marketer';
            })->first();

            // Lặp qua từng chi tiết sản phẩm trong đơn hàng và áp dụng logic khuyến mãi
            $order->orderDetail->map(function ($orderDetail) use ($mainRole) {
                $product = $orderDetail->product;

                // Lọc khuyến mãi dựa trên vai trò của người dùng
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

                return $orderDetail;
            });

            // Gửi email xác nhận đơn hàng (giả lập)
            Mail::to($user->email)->send(new OrderCreatedMail($order));

            return response()->json([
                'status' => 'success',
                'message' => 'Email xác nhận đơn hàng đã được gửi',
                'data' => $order
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
            
            $totalCostSum = 0;
            if ($request->status == 'delivered') {
                $totalCostSum = Order::where('user_id', $order->user_id)->where('status', 'delivered')->sum('total_cost');
                if ($totalCostSum >= 10000000) {
                    $user = User::where('id', $order->user_id)->first();
                    $roles = $user->getRoleNames();
                    if (!in_array('loyal_customer', $roles)) {
                        $user->removeRole('normal_user');
                        $user->assignRole('loyal_customer');

                        $notification = new Notification();
                        $notification->user_id = $order->user_id;
                        $notification->message = 'Chúc mừng bạn đã trở thành khách hàng thân thiết';
                        $notification->type = 'user';
                        $notification->route_name = '';
                        $notification->save();
                    }
                }
            }
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
