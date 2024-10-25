<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Order;

class OrderCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $orderAddress = json_decode($this->order->order_address, true);

        return $this->subject('Xác nhận đặt hàng thành công đơn hàng #' . $this->order->bill_id)
            ->view('emails.orders.created')
            ->with([
                'order' => $this->order,
                'orderDate' => $this->order->created_at->format('d/m/Y H:i:s'),
                'orderDetails' => $this->order->orderDetail->map(function ($orderDetail) {
                    // Kiểm tra nếu tồn tại khuyến mãi
                    $promotion = $orderDetail->product->product_promotion->first();
                    $discountPrice = $promotion ? $promotion->discount_price : 0;

                    return [
                        'product_name' => $orderDetail->product->product_name,
                        'quantity' => $orderDetail->quantity,
                        'original_price' => $orderDetail->product->product_price,
                        'discount_price' => $discountPrice,
                        'final_price' => ($orderDetail->product->product_price - $discountPrice) * $orderDetail->quantity
                    ];
                }),
                'shippingFee' => $this->order->shipping_fee,
                'totalCost' => $this->order->total_cost,
                'customerName' => $orderAddress['name'] ?? 'Khách hàng',
                'orderAddress' => $orderAddress,
            ]);
    }
}
