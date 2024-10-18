<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Console\Command;
use App\Events\Order\UpdateOrderStatusFailed;
use App\Http\Controllers\API\Order\OrderDetailController;

class UpdateOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-order-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update order status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $orders = Order::where('status', 'pending_payment')->get();
        \Log::info('Updating order statuses...');
        foreach ($orders as $order) {
            $expiryTime = Carbon::parse($order->created_at)->addMinutes(10);

            if ($now->greaterThan($expiryTime)) {
                $order->status = 'payment_failed';
                $order->save();
                $orderDetailController = new OrderDetailController();
                $orderDetailController->revertStock($order->order_id);
                $notification = new Notification();
                $notification->user_id = $order->user_id;
                $notification->message = 'Đơn hàng thanh toán không thành công!';
                $notification->type = 'user';
                $notification->route_name = 'profile';
                $notification->save();
                // Dispatch event gửi thông báo đến người dùng
                event(new UpdateOrderStatusFailed($order));
                $this->info("Order ID {$order->id} has been updated to payment_failed.");
            }
        }
    }
}
