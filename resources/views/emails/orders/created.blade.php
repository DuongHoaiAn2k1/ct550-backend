<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đặt hàng thành công đơn hàng #{{ $order->bill_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #333;
        }
        .order-details {
            margin-top: 20px;
        }
        .order-details ul {
            list-style-type: none;
            padding: 0;
        }
        .order-details ul li {
            margin-bottom: 10px;
        }
        .total-cost {
            font-weight: bold;
        }
        .payment-status {
            margin-top: 20px;
            font-weight: bold;
            color: #007b00; /* Màu xanh cho trạng thái đã thanh toán */
        }
        .payment-status.pending {
            color: #ff0000; /* Màu đỏ cho trạng thái chờ thanh toán */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Xin chào khách hàng #{{ $customerName  }}</h2>
        <p>Cảm ơn Quý Khách đã tin tưởng và đặt hàng tại website chúng tôi!</p>

        <p><strong>Thông tin đơn hàng:</strong></p>
        <p><strong>Mã đơn hàng:</strong> #{{ $order->bill_id }}</p>
        <p><strong>Tiền ship:</strong> {{ number_format($shippingFee, 0, ',', '.') }} VND</p>
        <p class="total-cost"><strong>Tổng tiền:</strong> {{ number_format($totalCost, 0, ',', '.') }} VND</p>
        <p class="payment-status {{ $order->pay_method == 'vnpay' ? '' : 'pending' }}">
            <strong>Trạng thái thanh toán:</strong> 
            {{ $order->pay_method == 'vnpay' ? 'Đã thanh toán' : 'Chờ thanh toán' }}
        </p>
        <div class="order-details">
            <h2>Chi tiết sản phẩm:</h2>
            <ul>
                @foreach ($orderDetails as $detail)
                    <li>
                        <strong>Tên sản phẩm:</strong> {{ $detail->product->product_name }}<br>
                        <strong>Số lượng:</strong> {{ $detail->quantity }}<br>
                        <strong>Thành tiền:</strong> {{ number_format($detail->product->product_price, 0, ',', '.') }} VND
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</body>
</html>
