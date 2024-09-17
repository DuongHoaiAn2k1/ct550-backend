<?php

namespace App\Http\Controllers\API\Refund;

use Illuminate\Http\Request;
use App\Models\RefundRequest;
use App\Http\Controllers\Controller;

class RefundRequestController extends Controller
{
    public function getAll()
    {
        try {
            $refundRequests = RefundRequest::orderBy('created_at', 'desc')->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy danh sách yêu cầu hoàn tiền thành công',
                'data' => $refundRequests
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getToday()
    {
        try {
            $today = now()->format('Y-m-d');
            $refundRequests = RefundRequest::whereDate('created_at', $today)->orderBy('created_at', 'desc')->get();
            $dataLength = count($refundRequests);
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy danh sách yêu cầu hoàn tiền trong ngày',
                'data' => $refundRequests,
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
            $refundRequest = new RefundRequest();
            $refundRequest->user_id = auth()->user()->id;
            $refundRequest->order_id = $request->order_id;
            $refundRequest->bill_id = $request->bill_id;
            $refundRequest->refund_request_status = 'pending';
            $refundRequest->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Đã tạo yêu cầu hoàn tiền',
                'data' => $refundRequest
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $refund_request_id)
    {
        try {
            RefundRequest::where('refund_request_id', $refund_request_id)->update([
                'refund_request_status' => $request->refund_request_status
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Đã thay đổi trảng thái yêu cầu hoàn tiền'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getRefundsBetweenDates(Request $request)
    {
        try {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $refundRequests = RefundRequest::whereBetween('created_at', [$start_date, $end_date])->orderBy('created_at', 'desc')->get();
            $dataLength = count($refundRequests);
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy sanh sách thành công theo thời gian',
                'data' => $refundRequests,
                'length' => $dataLength
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
