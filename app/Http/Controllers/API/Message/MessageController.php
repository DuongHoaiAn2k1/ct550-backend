<?php

namespace App\Http\Controllers\API\Message;

use App\Models\User;
use App\Models\Message;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Events\Message\MessageSent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{


    public function getMessage()
    {
        try {
            $userId = auth()->user()->id;
            $messages = Message::where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'desc')
                ->get();

            Message::where('receiver_id', $userId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

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

    public function countUnUserUnreadMessage()
    {
        try {
            $userId = auth()->user()->id;
            $count = Message::where(function ($query) use ($userId) {
                $query->where('receiver_id', $userId);
            })
                ->whereNull('read_at')
                ->count();
            return response()->json([
                'status' => 'success',
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' =>  $e->getMessage(),
            ], 500);
        }
    }

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

            Message::where('receiver_id', operator: env('ADMIN_ID'))
                ->where('sender_id', $userId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
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


    public function adminReadMessage($userId)
    {
        try {
            Message::where('receiver_id', operator: env('ADMIN_ID'))
                ->where('sender_id', $userId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return response()->json([
                'status' => 'success',
                'message' => 'Read message success',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
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
                $receiverId = $this->getAdminId();
            } else {
                $receiverId = $request->receiver_id;
                if (!$receiverId || !User::find($receiverId)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid receiver ID.',
                    ], 422);
                }
            }

            // Kiểm tra và chuẩn bị dữ liệu sản phẩm
            $products = null;
            if ($request->has('products') && is_array($request->products)) {
                $products = json_encode($request->products, JSON_UNESCAPED_UNICODE); // Chuyển mảng thành JSON
            }

            // Tạo tin nhắn mới
            $message = Message::create([
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
                'message' => $request->message,
                'products' => $products,
            ]);

            // Gửi sự kiện
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

    public function sendToChatbot(Request $request)
    {
        try {
            $userId = auth()->user()->id;

            // Validate yêu cầu
            $validator = Validator::make($request->all(), [
                'message' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $userMessage = $request->input('message');

            // Gửi yêu cầu đến chatbot
            $response = $this->callChatbotApi($userMessage, $userId);

            // Chuyển phản hồi thành mảng
            $responseArray = json_decode(json_encode($response), true);

            if (empty($responseArray)) {
                \Log::error('Chatbot API response invalid: ' . json_encode($responseArray));
                return response()->json([
                    'status' => 'error',
                    'message' => 'Chatbot không phản hồi đúng định dạng.',
                ], 500);
            }

            // Lưu tin nhắn của người dùng
            $message = Message::create([
                'sender_id' => $userId,
                'receiver_id' => 1,
                'message' => $userMessage,
                'is_bot' => false,
            ]);

            sleep(1);
            // Kiểm tra nếu phản hồi chứa sản phẩm
            if (isset($responseArray[0]['custom']['results'])) {
                $results = $responseArray[0]['custom']['results'];

                $filteredProducts = collect($results)
                    ->filter(function ($item) {
                        return $item['similarity'] > 0.15;
                    })
                    ->sortByDesc('similarity')
                    ->take(3)
                    ->map(function ($item) {
                        // Truy vấn thông tin sản phẩm từ cơ sở dữ liệu
                        $product = Product::find($item['product_id']);

                        if ($product) {
                            $firstImage = $product->product_img;

                            // Nếu product_img là JSON hoặc chuỗi, xử lý để lấy hình ảnh đầu tiên
                            if ($this->isJson($firstImage)) {
                                $images = json_decode($firstImage, true); // Giải mã JSON thành mảng
                                $firstImage = $images[0] ?? ''; // Lấy hình ảnh đầu tiên
                            } else {
                                $firstImage = explode(',', $firstImage)[0]; // Nếu là chuỗi, tách bằng dấu phẩy
                            }

                            return [
                                'product_id' => $product->product_id,
                                'product_img' => $firstImage, // Lấy hình ảnh đầu tiên
                                'product_name' => $product->product_name,
                                'product_price' => $product->product_price,
                                'similarity' => $item['similarity'],
                            ];
                        }

                        return null; // Bỏ qua sản phẩm nếu không tìm thấy
                    })
                    ->filter() // Loại bỏ các mục null nếu sản phẩm không tồn tại
                    ->values()
                    ->toArray();

                $messageCustome = 'Đây là một số sản phẩm mà bạn có thể tham khảo'; // Mặc định thông điệp
                foreach ($filteredProducts as $product) {
                    if ($product['similarity'] == 3) {
                        $messageCustome = 'Sản phẩm có giá cao nhất là'; // Thay đổi thông điệp nếu similarity = 3
                        break;
                    }

                    if ($product['similarity'] == 2) {
                        $messageCustome = 'Sản phẩm có giá thấp nhất là'; // Thay đổi thông điệp nếu similarity = 2
                        break;
                    }

                    if ($product['similarity'] == 4) {
                        $messageCustome = 'Sản phẩm bán chạy nhất là';
                        break;
                    }

                    if ($product['similarity'] == 5) {
                        $messageCustome = 'Sản phẩm yêu thích nhất là';
                        break;
                    }
                }

                if (empty($filteredProducts)) {
                    // Trường hợp không có sản phẩm phù hợp
                    Message::create([
                        'sender_id' => 1, // Chatbot
                        'receiver_id' => $userId, // Người dùng
                        'message' => 'Xin lỗi, chúng tôi không cung cấp sản phẩm mà bạn yêu cầu.',
                        'is_bot' => true,
                        'products' => null, // Không lưu danh sách sản phẩm
                    ]);
                } else {
                    // Trường hợp có sản phẩm phù hợp
                    Message::create([
                        'sender_id' => 1, // Chatbot
                        'receiver_id' => $userId, // Người dùng
                        'message' => $messageCustome, // Gửi thông điệp đã thay đổi
                        'is_bot' => true,
                        'products' => json_encode($filteredProducts, JSON_UNESCAPED_UNICODE),
                    ]);
                }


                event(new MessageSent($message));
                return response()->json([
                    'status' => 'success',
                    'data' => $filteredProducts,
                ]);
            } else {
                // Nếu không có sản phẩm, lưu phản hồi text
                $botMessage = $responseArray[0]['text'] ?? 'Chatbot không trả lời.';
                Message::create([
                    'sender_id' => 1, // Chatbot
                    'receiver_id' => $userId, // Người dùng
                    'message' => $botMessage,
                    'is_bot' => true,
                ]);
                event(new MessageSent($message));
                return response()->json([
                    'status' => 'success',
                    'data' => $botMessage,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error communicating with chatbot: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error communicating with chatbot: ' . $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Hàm gửi yêu cầu đến API chatbot.
     */
    private function callChatbotApi($message, $userId)
    {
        $chatbotApiUrl = "http://rasa:5005/webhooks/rest/webhook";

        $client = new \GuzzleHttp\Client();

        $response = $client->post($chatbotApiUrl, [
            'json' => [
                'user_id' => $userId,
                'message' => $message,
            ],
            'timeout' => 10, // Thời gian chờ API
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
