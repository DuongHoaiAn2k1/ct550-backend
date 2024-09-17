<?php

namespace App\Http\Controllers\API\Message;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\Message\MessageSent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * Lấy danh sách tin nhắn của người dùng và hệ thống.
     */
    public function getUserMessages($userId)
    {
        try {


            $messages = Message::where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $messages,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' =>  $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Lấy danh sách tất cả tin nhắn của người dùng có role 'normal_user', 'loyal_customer', hoặc 'affiliate_marketer'.
     */
    public function getAllUserMessagesByRole()
    {
        try {
            $user = User::with('roles')->get();

            return response()->json([
                'status' => 'success',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Could not retrieve messages: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Tạo tin nhắn mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        try {

            if ($request->message == null) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Message is empty'
                ], 422);
            }

            $senderId = auth()->user()->id;

            // Xác định người nhận
            if ($this->isUser($senderId)) {
                // Nếu người gửi là người dùng (normal_user, loyal_user, affiliate_marketer)
                $receiverId = $this->getAdminId();
            } else {
                // Nếu người gửi là admin hoặc staff, cần truyền receiver_id từ request
                $receiverId = $request->receiver_id;

                // Xác thực xem người nhận có hợp lệ không
                if (!$receiverId || !User::find($receiverId)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid receiver ID.',
                    ], 422);
                }
            }

            // Tạo tin nhắn mới
            $message = Message::create([
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
                'message' => $request->message,
            ]);


            event(new MessageSent($message));

            return response()->json([
                'status' => 'success',
                'data' => $message,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Could not send message: ' . $e->getMessage(),
            ], 500);
        }
    }


    private function isUser($userId)
    {
        return User::find($userId)->hasRole(['normal_user', 'loyal_customer', 'affiliate_marketer']);
    }

    /**
     * Lấy ID của admin đầu tiên.
     */
    private function getAdminId()
    {
        return User::role('admin')->first()->id;
    }

    /**
     * Xóa tin nhắn theo ID.
     */
    public function destroy($id)
    {
        try {
            $message = Message::findOrFail($id);
            $message->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Message deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Could not delete message: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getUsersWithMessages()
    {
        try {
            $userIds = User::role(['normal_user', 'loyal_customer', 'affiliate_marketer'])->pluck('id');

            $usersWithMessages = Message::whereIn('sender_id', $userIds)
                ->orWhereIn('receiver_id', $userIds)
                ->distinct()
                ->pluck('sender_id', 'receiver_id')
                ->flatten()
                ->unique();

            $users = User::whereIn('id', $usersWithMessages)
                ->whereIn('id', $userIds)
                ->get();

            $users->each(function ($user) {
                $user->role = $user->getRoleNames()->first();
            });

            return response()->json([
                'status' => 'success',
                'data' => $users,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Could not retrieve users with messages: ' . $e->getMessage(),
            ], 500);
        }
    }
}
