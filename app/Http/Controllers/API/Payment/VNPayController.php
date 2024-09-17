<?php

namespace App\Http\Controllers\API\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VNPayController extends Controller
{
    /**
     * Tạo URL Thanh Toán
     */
    public function createPayment(Request $request)
    {
        // \Log::info('VNPay Request Data: ', $request->all());

        $vnp_TmnCode = env('VNPAY_TMN_CODE');
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $vnp_Url = env('VNPAY_URL');
        $vnp_Returnurl = env('VNPAY_RETURN_URL');

        $vnp_TxnRef = $request->input('order_id');
        $vnp_OrderInfo = $request->input('order_desc');
        $vnp_OrderType = $request->input('order_type');
        $vnp_Amount = $request->input('amount') * 100;
        $vnp_Locale = $request->input('language', 'vn');
        // $vnp_BankCode = $request->input('bank_code');
        $vnp_IpAddr = $request->ip();

        $vnp_ExpireDate = $request->input('txtexpire');
        $vnp_Bill_Mobile = $request->input('txt_billing_mobile');
        $vnp_Bill_Email = $request->input('txt_billing_email');
        $fullName = trim($request->input('txt_billing_fullname'));
        $vnp_Bill_FirstName = '';
        $vnp_Bill_LastName = '';

        if (!empty($fullName)) {
            $name = explode(' ', $fullName);
            $vnp_Bill_FirstName = array_shift($name);
            $vnp_Bill_LastName = implode(' ', $name);
        }

        $vnp_Bill_Address = $request->input('txt_inv_addr1');
        $vnp_Bill_City = $request->input('txt_bill_city');
        $vnp_Bill_Country = $request->input('txt_bill_country');
        $vnp_Bill_State = $request->input('txt_bill_state');
        $vnp_Inv_Phone = $request->input('txt_inv_mobile');
        $vnp_Inv_Email = $request->input('txt_inv_email');
        $vnp_Inv_Customer = $request->input('txt_inv_customer');
        $vnp_Inv_Address = $request->input('txt_inv_addr1');
        $vnp_Inv_Company = $request->input('txt_inv_company') || ' ';
        $vnp_Inv_Taxcode = $request->input('txt_inv_taxcode') || ' ';
        $vnp_Inv_Type = $request->input('cbo_inv_type');

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $vnp_ExpireDate,
            "vnp_Bill_Mobile" => $vnp_Bill_Mobile,
            "vnp_Bill_Email" => $vnp_Bill_Email,
            "vnp_Bill_FirstName" => $vnp_Bill_FirstName,
            "vnp_Bill_LastName" => $vnp_Bill_LastName,
            "vnp_Bill_Address" => $vnp_Bill_Address,
            "vnp_Bill_City" => $vnp_Bill_City,
            "vnp_Bill_Country" => $vnp_Bill_Country,
            "vnp_Inv_Phone" => $vnp_Inv_Phone,
            "vnp_Inv_Email" => $vnp_Inv_Email,
            "vnp_Inv_Customer" => $vnp_Inv_Customer,
            "vnp_Inv_Address" => $vnp_Inv_Address,
            "vnp_Inv_Company" => $vnp_Inv_Company,
            "vnp_Inv_Taxcode" => $vnp_Inv_Taxcode,
            "vnp_Inv_Type" => $vnp_Inv_Type
        );

        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        if (!empty($vnp_Bill_State)) {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        // Sắp xếp mảng dữ liệu theo thứ tự bảng chữ cái
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        // Tạo URL thanh toán
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return response()->json([
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url
        ]);
    }


    /**
     * Xử Lý Kết Quả Thanh Toán (Return URL)
     */
    public function handleReturn(Request $request)
    {
        // Lấy khóa bảo mật từ file cấu hình
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');

        // Lấy tất cả các tham số từ yêu cầu GET
        $inputData = $request->all();
        \Log::info('Input Data: ', $inputData);

        // Kiểm tra xem mã hash bảo mật có tồn tại không
        if (!isset($inputData['vnp_SecureHash'])) {
            return response()->json([
                'code' => '97',
                'message' => 'Mã hash bảo mật không được cung cấp.'
            ]);
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);

        // Sắp xếp dữ liệu theo thứ tự bảng chữ cái
        ksort($inputData);

        // Tạo chuỗi dữ liệu từ các tham số
        $hashData = '';
        foreach ($inputData as $key => $value) {
            if ($hashData == '') {
                $hashData .= urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            }
        }

        // Tính toán mã hash từ chuỗi dữ liệu sử dụng khóa bảo mật
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // So sánh mã hash tính toán được với mã hash từ yêu cầu
        if ($secureHash == $vnp_SecureHash) {
            // Kiểm tra mã phản hồi để xác định trạng thái giao dịch
            if ($inputData['vnp_ResponseCode'] == '00') {
                return response()->json([
                    'code' => '00',
                    'message' => 'Giao dịch thành công!'
                ]);
            } else {
                return response()->json([
                    'code' => $inputData['vnp_ResponseCode'],
                    'message' => 'Giao dịch không thành công.'
                ]);
            }
        } else {
            return response()->json([
                'code' => '97',
                'message' => 'Chuỗi mã hash không hợp lệ.'
            ]);
        }
    }








    /**
     * Xử Lý Thông Báo Thanh Toán (IPN URL)
     */
    public function handleIPN(Request $request)
    {
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');

        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $hashData = urldecode(http_build_query($inputData));
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash == $vnp_SecureHash) {
            if ($inputData['vnp_ResponseCode'] == '00') {
                return response()->json([
                    'RspCode' => '00',
                    'Message' => 'Confirm Success'
                ]);
            } else {
                return response()->json([
                    'RspCode' => '01',
                    'Message' => 'Confirm Failed'
                ]);
            }
        } else {
            return response()->json([
                'RspCode' => '97',
                'Message' => 'Invalid Signature'
            ]);
        }
    }
}
