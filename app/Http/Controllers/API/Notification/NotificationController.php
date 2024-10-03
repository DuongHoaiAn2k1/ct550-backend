<?php

namespace App\Http\Controllers\API\Notification;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{

    public function getByUser()
    {
        try {
            $user_id = auth()->user()->id;

            $notification = new Notification();
            $listData = $notification->where('user_id', $user_id)->orderBy('created_at', 'desc')->limit(20)->get();
            $count_unread = $notification->where('is_user_read', false)->count();
            return response()->json([
                'status' => 'success',
                'data' => $listData,
                'count_unread' => $count_unread
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
            $notification = new Notification();
            $listData = $notification->where('type', 'admin')->orderBy('created_at', 'desc')->limit(20)->get();
            $count_unread = $notification->where('is_admin_read', false)->count();

            return response()->json([
                'status' => 'success',
                'data' => $listData,
                'count_unread' => $count_unread
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

            $user_id = auth()->user()->id;
            $notification = new Notification();
            $validator = Validator::make($request->all(), [
                'message' => 'required',
                'route_name' => 'required',
                'type' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $notification->message = $request->message;
            $notification->route_name = $request->route_name;
            $notification->type = $request->type;

            $notification->user_id = $user_id;
            $notification->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Create Notification Successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {

        try {
            Notification::where('id', $id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Hủy động bằng dữ liệu'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function getByAdminType($type)
    {
        try {
            $notification = new Notification();
            $listData = $notification->get()->where('type', $type);
            return response()->json([
                'status' => 'success',
                'data' => $listData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function adminReadAll()
    {
        try {
            $notifications = Notification::where('is_admin_read', false)->get();
            foreach ($notifications as $item) {
                $item->is_admin_read = true;
                $item->save();
            }


            return response()->json([
                'status' => 'success',
                'message' => 'Update admin read all'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function userReadAll()
    {
        try {
            $notifications = Notification::where('is_user_read', false)->get();
            foreach ($notifications as $item) {
                $item->is_user_read = true;
                $item->save();
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Update user read all'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ],
                500
            );
        }
    }
}
