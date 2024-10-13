<!DOCTYPE html>
<html>
<head>
    <title>Mật khẩu mới được cấp</title>
</head>
<body>
    <h2>Xin chào, {{ $user->name }}</h2>
    <p>Mật khẩu mới của bạn đã được tạo thành công.</p>
    <p>Mật khẩu đăng nhập: <strong>{{ $newPassword }}</strong></p>
    <p>Để đảm bảo an toàn, tuyệt đối không cung cấp mật khẩu này cho bất kỳ ai.</p>
    <p>Lưu ý: Bạn chỉ có thể đặt lại mật khẩu sau 30 ngày tiếp theo.</p>
    <br>
    <p>Xin cảm ơn</p>
</body>
</html>
