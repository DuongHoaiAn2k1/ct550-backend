<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mã OTP của bạn</title>
    <style>
        /* Thêm các định dạng CSS tùy chỉnh của bạn ở đây */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .otp-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 20px;
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <h2>Xác Nhận Email của Bạn</h2>
        <p>Xin chào!</p>
        <p>Bạn vừa yêu cầu một mã OTP để tiếp tục quá trình đăng ký. Mã OTP của bạn là:</p>
        <div style="font-size: 24px; margin: 20px 0; padding: 10px; background-color: #e9ecef; border-radius: 5px; display: inline-block;">
            {{ $otp }}
        </div>
        <p>Vui lòng nhập mã này vào trang web để hoàn tất quá trình đăng ký.</p>
        <p>Nếu bạn không yêu cầu mã này, vui lòng bỏ qua email này.</p>
        <p>Xin cảm ơn!</p>
    </div>
</body>
</html>
