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
        <p><strong>Ngày đặt hàng:</strong> {{ $orderDate }}</p>
        <p><strong>Tiền ship:</strong> {{ number_format($shippingFee, 0, ',', '.') }} VND</p>
        <p class="total-cost"><strong>Tổng tiền:</strong> {{ number_format($totalCost, 0, ',', '.') }} VND</p>
        <p class="payment-status {{ $order->pay_method == 'vnpay' ? '' : 'pending' }}">
            <strong>Trạng thái thanh toán:</strong> 
            {{ $order->pay_method == 'vnpay' ? 'Đã thanh toán' : 'Chờ thanh toán' }}
        </p>
        <div class="shipping-address">
            <h3>Địa chỉ nhận hàng:</h3>
            <p><strong>Người nhận:</strong> {{ $orderAddress['name'] }}</p>
            <p><strong>Số điện thoại:</strong> {{ $orderAddress['phone'] }}</p>
            <p><strong>Địa chỉ:</strong> {{ $orderAddress['address'] }}, {{ $orderAddress['commue'] }}, {{ $orderAddress['district'] }}, {{ $orderAddress['city'] }}</p>
        </div>
        <div class="order-details">
            <h2>Chi tiết sản phẩm:</h2>
            <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="text-align: left;">Tên sản phẩm</th>
                        <th style="text-align: center;">Số lượng</th>
                        <th style="text-align: center;">Giá gốc (VND)</th>
                        <th style="text-align: center;">Khuyến mãi (VND)</th>
                        <th style="text-align: center;">Thành tiền (VND)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderDetails as $detail)
                        <tr>
                            <td>{{ $detail['product_name'] }}</td>
                            <td style="text-align: center;">{{ $detail['quantity'] }}</td>
                            <td style="text-align: center;">{{ number_format($detail['original_price'], 0, ',', '.') }}</td>
                            @if($detail['discount_price'] > 0)
                                <td style="text-align: center;">- {{ number_format($detail['discount_price'], 0, ',', '.') }}</td>
                                <td style="text-align: center;">{{ number_format($detail['final_price'], 0, ',', '.') }}</td>
                            @else
                                <td style="text-align: center;">Không có</td>
                                <td style="text-align: center;">{{ number_format($detail['final_price'], 0, ',', '.') }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>
</body>
</html>
