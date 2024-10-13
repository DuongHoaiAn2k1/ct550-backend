<?php

namespace App\Http\Controllers\API\User;

use App\Models\User;
use App\Mail\OtpMail;
use App\Rules\HasChar;
use App\Models\Message;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Events\User\UserRegistered;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function register(Request $request)
    {
        $customMessages = [
            'name.required' => 'Vui lòng nhập họ tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.max' => 'Mật khẩu tối đa 20 ký tự',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'min:6', 'max:20', new HasChar],
        ], $customMessages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $errors
            ], 422);
        } else {
            try {
                if (User::where('email', $request->email)->exists()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Email đã tồn tại',
                    ], 422);
                } else {
                    if (isset($request->otp)) {
                        $storedOtp = Redis::get('otp_' . $request->email);
                        if ($storedOtp != $request->otp) {
                            return response()->json([
                                'status' => 'ErrorOTP',
                                'message' => 'Mã OTP không chính xác',
                                // 'storedOtp' => $storedOtp,  // Debug info
                                // 'inputOtp' => $request->otp  // Debug info
                            ]);
                        } else {
                            $user = new User();
                            $user->name = $request->name;
                            $user->email = $request->email;
                            $user->password = bcrypt($request->password);
                            $user->assignRole('normal_user');
                            $user->save();

                            event(new UserRegistered());

                            return response()->json([
                                'status' => 'success',
                                'message' => 'Create customer successfully',
                            ], 201);
                        }
                    } else {
                        $otp = rand(100000, 999999);
                        Mail::to($request->email)->send(new OtpMail($otp));
                        Redis::setex('otp_' . $request->email, 120, $otp); // Lưu OTP vào Redis với thời gian sống là 120 giây
                        // Log OTP đã lưu
                        // \Log::info('OTP stored in Redis', ['email' => $request->email, 'otp' => $otp]);
                        return response()->json([
                            'status' => 'pending',
                            'message' => 'OTP đã được gửi tới email. Vui lòng xác minh email',
                        ], 201);
                    }
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function resetPassword(Request $request)
    {
        if (!isset($request->email)) {
            return response()->json(['status' => 'error', 'message' => 'Email not found'], 404);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Email not found'], 404);
        }
        // Check if the user has already reset their password within the last month
        if ($user->last_password_reset && $user->last_password_reset > Carbon::now()->subMonth()) {
            return response()->json(['status' => 'error', 'message' => 'Bạn đã thay đổi mật khẩu trong vòng 30 ngày qua.'], 403);
        }

        // Generate a new password
        $newPassword = $this->generatePassword();

        // Update the user's password
        $user->password = bcrypt($newPassword);
        $user->last_password_reset = Carbon::now();
        $user->save();

        // Send the new password to the user's email
        Mail::send('emails.reset_password', ['user' => $user, 'newPassword' => $newPassword], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Cấp lại mật khẩu');
        });

        return response()->json(['status' => 'success', 'message' => 'Password reset successfully. Please check your email.']);
    }

    public function index($id)
    {
        try {
            $data = User::find($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Fetch data successfully',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getListUser()
    {
        try {
            $listUser = User::role(['normal_user', 'loyal_customer'])->get();
            $listUser->each(function ($user) {
                $user->role = $user->getRoleNames()->first();
            });
            return response()->json([
                'status' => 'success',
                'message' => 'Fetch list user successfully',
                'data' => $listUser
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getListUsersByRole($role)
    {
        try {
            $listUser = User::role($role)->get();
            $listUser->each(function ($user) {
                $user->role = $user->getRoleNames()->first();
            });
            return response()->json([
                'status' => 'success',
                'message' => 'Fetch list user successfully',
                'data' => $listUser
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
            $role = auth()->user()->roles;
            if ($role != 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Quyền này không thuộc về bạn'
                ], 500);
            }
            $users = User::where('roles', '<>', 0)->get();
            $list_order = null;
            // $list_order = Order::get();
            $dataLength = count($users);
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy danh sách người dùng thành công',
                'data' => $users,
                'length' => $dataLength,
                'list_order' => $list_order
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function createAddress(Request $request)
    {
        try {
            $customerMessages = [
                'name.required' => 'Tên người nhận không được để trống.',
                'phone.required' => 'Số điện thoại không được để trống.',
                'city.required' => 'Thành phố không được để trống.',
                'district.required' => 'Quận huyện không được để trống.',
                'commue.required' => 'Phường xã không được để trống.',
                'address.required' => 'Địa chỉ cụ thể không được để trống'
            ];
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required',
                'city' => 'required',
                'commue' => 'required',
                'district' => 'required',
                'address' => 'required',
            ], $customerMessages);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ], 422);
            }

            $data = $request->all();
            $userId = auth()->user()->id;
            $user = User::find($userId);
            $userAddresses = json_decode($user->address, true) ?? [];

            $userAddresses[] = $data;
            $user->address = $userAddresses;
            $user->save();
            // DB::table('users')
            //     ->where('id', $userId)
            //     ->update(['address' => json_encode($userAddresses)]);
            // $user->update([
            //     'address' => json_encode($userAddresses)
            // ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Thêm địa chỉ thành công',
                'data' => $data,
                'userAddresses' => $userAddresses,
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $user_id = auth()->user()->id;
            $name = $request->input('name');
            \Log::info('Request Data:', $request->all()); // Check request data

            if (empty($name)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Name is empty',
                ], 500);
            }

            $user = User::where('id', $user_id)->first();

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('uploads', 'public');
                $user->image = $imagePath;
            }
            $user->name = $name;
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Name was updated'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Update Error: ' . $e->getMessage()); // Log detailed error
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateNameAndPhone(Request $request)
    {
        try {
            $user_id = auth()->user()->id;
            $name = $request->input('name');
            $phone = $request->input('phone');

            $user = User::where('id', $user_id)->first();
            $user->name = $name;
            $user->phone = $phone;
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Name and phone was updated'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function update_pass(Request $request)
    {
        try {
            $customerMessages = [
                'new_pass.required' => "Mật khẩu không được để trống",
                'new_pass.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
                'new_pass.max' => "Mật khẩu phải tối đa 32 ký tự",
                'old_pass.required' => "Mật khẩu không được để trống",
                'old_pass.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
                'old_pass.max' => "Mật khẩu phải tối đa 32 ký tự",
            ];

            $validator = Validator::make($request->all(), [
                'new_pass' => 'required|min:8|max:32',
                'old_pass' => 'required|min:8|max:32'
            ], $customerMessages);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ], 442);
            }

            $user_id = auth()->user()->id;
            $new_pass = $request->new_pass;
            $old_pass = $request->old_pass;

            if (!auth()->attempt(['id' => $user_id, 'password' => $old_pass])) {
                return response()->json(['error' => 'Password is incorrect'], 500);
            }

            $user = User::where("id", $user_id)->first();
            $user->password = bcrypt($new_pass);
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Pass'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteAddress($index)
    {
        try {
            $userId = auth()->user()->id;
            $user = User::find($userId);
            $userAddresses = json_decode($user->address, true) ?? [];

            if ($index < 0 || $index >= count($userAddresses)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid index provided'
                ], 422);
            }

            array_splice($userAddresses, $index, 1);

            // $user->update([
            //     'address' => json_encode($userAddresses)
            // ]);
            $user->address = $userAddresses;
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Đã xóa địa chỉ thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function incrementPoint()
    {
        try {
            $user_id = auth()->user()->id;
            $user = User::where('id', $user_id)->first();
            $user->point = $user->point + 10;
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Point Increased Successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function decrementPoint(Request $request)
    {
        try {
            $user_id = auth()->user()->id;
            $user = User::where('id', $user_id)->first();
            $point_used = $request->point_used;

            $user->point = $user->point - $point_used;
            $user->point_used = $user->point_used + $point_used;
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Point Updated Successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function restorePoint(Request $request)
    {
        try {
            $user_id = auth()->user()->id;
            $user = User::where('id', $user_id)->first();
            $point_paid = $request->point_paid;
            $user->point_used = $user->point_used - $point_paid;
            $user->point = $user->point + $point_paid;
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Restore successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getCurrentPoint()
    {
        try {
            $user_id = auth()->user()->id;
            $user = User::where('id', $user_id)->first();
            return response()->json([
                'status' => 'success',
                'message' => 'Get Current Point SuccessFully',
                'point' => $user->point,
                'used_point' => $user->point_used
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete_user($user_id)
    {
        try {
            $user = User::where('id', $user_id)->first();
            $user->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Delete user successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function getListUserWithRole()
    {
        try {
            $users = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['normal_user', 'loyal_customer', 'affiliate_marketer']);
            })
                ->where(function ($query) {
                    $query->whereHas('messagesSent')
                        ->orWhereHas('messagesReceived');
                })
                ->with('roles')
                ->get();

            $users->each(function ($user) {
                $latestMessage = Message::where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                $user->latest_message_time = $latestMessage ? $latestMessage->created_at : null;


                $user->unread_count = Message::where('receiver_id', $user->id)
                    ->whereNull('read_at')
                    ->count();

                $adminId = env('ADMIN_ID');
                $user->unread_count_from_admin = Message::where('sender_id', $user->id)
                    ->where('receiver_id', $adminId)
                    ->whereNull('read_at')
                    ->count();
            });

            $users = $users->sortByDesc(function ($user) {
                return $user->latest_message_time;
            })->values(); // Reset keys

            return response()->json([
                'status' => 'success',
                'data' => $users,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getListAffiliate()
    {
        try {
            $user = User::whereHas('roles', function ($query) {
                $query->where('name', '=', 'affiliate_marketer');
            })->with('roles')->with('affiliateWallets')->get();
            return response()->json([
                'status' => 'success',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function generatePassword($length = 10)
    {
        do {

            $password = Str::random($length);
        } while (!$this->isValidPassword($password));

        return $password;
    }

    private function isValidPassword($password)
    {
        return strlen($password) > 6
            && preg_match('/[a-zA-Z]/', $password)
            && preg_match('/[0-9]/', $password);
    }
}
