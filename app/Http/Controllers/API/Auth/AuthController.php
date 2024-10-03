<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Services\GoogleLoginService;
use App\Services\FacebookLoginService;

class AuthController extends Controller
{
    protected $googleLoginService;
    protected $facebookLoginService;
    public function __construct(GoogleLoginService $googleLoginService, FacebookLoginService $facebookLoginService)
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh', 'checkRefreshTokenExpiration', 'redirectToGoogle', 'handleGoogleCallback', 'redirectToFacebook', 'handleFacebookCallback', 'me', 'check']]);
        $this->googleLoginService = $googleLoginService;
        $this->facebookLoginService = $facebookLoginService;
    }


    public function login(Request $request)
    {
        // $credentials = request(['email', 'password']);
        try {
            if (isset($request->email)) {
                $customerMessages = [
                    'email.required' => "Email không được để trống",
                    'password.required' => 'Mật khẩu không được để trống',
                    'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
                    'password.max' => 'Mật khẩu tối đa 32 ký tự',
                ];

                $validator = Validator::make($request->all(), [
                    'email' => 'required|email',
                    'password' => 'required|min:8|max:32'
                ], $customerMessages);

                if ($validator->fails()) {
                    $errors = $validator->errors();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validation failed',
                        'errors' => $errors,
                    ], 442);
                } else {
                    $credentials = $request->only('email', 'password');
                }
                if (!$token = auth()->attempt($credentials)) {
                    return response()->json(['error' => 'Email or Password is incorrect'], 500);
                }
                $user_id = auth()->user()->id;
                $refreshToken = $this->createRefreshToken();
                return $this->respondWithToken($token, $refreshToken, $user_id);
            } else {
                $customerMessages = [
                    'username.required' => "Email không được để trống",
                    'password.required' => 'Mật khẩu không được để trống',
                    'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
                    'password.max' => 'Mật khẩu tối đa 32 ký tự',
                ];

                $validator = Validator::make($request->all(), [
                    'username' => 'required',
                    'password' => 'required|min:8|max:32'
                ], $customerMessages);

                if ($validator->fails()) {
                    $errors = $validator->errors();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validation failed',
                        'errors' => $errors,
                    ], 442);
                } else {
                    $credentials = $request->only('username', 'password');
                }
                if (!$token = auth()->attempt($credentials)) {
                    return response()->json(['error' => 'Username or password is incorrect'], 500);
                }
                $user_id = auth()->user()->id;

                $refreshToken = $this->createRefreshToken();
                return $this->respondWithToken($token, $refreshToken, $user_id);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function me()
    {
        try {
            return response()->json(auth()->user());
        } catch (JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }


    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    public function refresh(Request $request)
    {
        $refreshToken = $request->refresh_Token;
        try {
            $decoded = JWTAuth::getJWTProvider()->decode($refreshToken);
            if (isset($decoded['exp']) && $decoded['exp'] > time()) {
                $user = User::find($decoded['user_id']);
                if (!$user) {
                    return response()->json([
                        "message" => "User Invalid"
                    ], 404);
                }

                $token = auth()->login($user);
                $refreshToken = $this->createRefreshToken();
                return $this->respondWithToken($token, $refreshToken, $decoded['user_id']);
            } else {
                return response()->json([
                    "status" => "error",
                    "message" => "Refresh token expired"
                ], 417);
            }

            // $accessToken = $request->bearerToken();
            // if ($accessToken) {
            //     try {
            //         JWTAuth::setToken($accessToken);
            //         $payload = JWTAuth::getPayload();
            //         auth()->invalidate();
            //     } catch (JWTException $e) {
            //         return response()->json(['message' => $e->getMessage()], 500);
            //     }
            // }

            // auth()->invalidate();

        } catch (JWTException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    protected function respondWithToken($token, $refreshToken, $userId)
    {
        $roles = auth()->user()->getRoleNames();

        $affiliateRole = in_array('affiliate_marketer', $roles->toArray()) ? 'affiliate_marketer' : null;

        $mainRole = $roles->filter(function ($role) {
            return $role !== 'affiliate_marketer';
        })->first();

        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'user_id' => $userId,
            'email' => auth()->user()->email,
            'role' => base64_encode($mainRole),
            'affiliate_role' => $affiliateRole,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


    private function createRefreshToken()
    {
        $data = [
            'user_id' => auth()->user()->id,
            'random' => rand() . time(),
            'exp' => time() +  config('jwt.refresh_ttl')
        ];

        $refreshToken = JWTAuth::getJWTProvider()->encode($data);
        return $refreshToken;
    }

    public function checkRefreshTokenExpiration(Request $request)
    {
        try {

            $refreshToken = $request->refresh_Token;
            $decoded = JWTAuth::getJWTProvider()->decode($refreshToken);

            if (isset($decoded['exp']) && $decoded['exp'] > time()) {
                return response()->json([

                    'status' => 'success',
                    'message' => "true",
                    'time' => $decoded['exp'],
                    'curent_time' => time()
                ]);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => "false",
                    'time' => $decoded['exp'],
                    'curent_time' => time()
                ]);
            }
        } catch (\Exception $e) {
            // Xảy ra lỗi khi giải mã token
            return false;
        }
    }

    public function redirectToGoogle()
    {
        return $this->googleLoginService->redirectToGoogle();
    }

    public function handleGoogleCallback()
    {
        return $this->googleLoginService->handleGoogleCallback();
    }

    public function redirectToFacebook()
    {
        return $this->facebookLoginService->redirectToFacebook();
    }

    public function handleFacebookCallback()
    {
        return $this->facebookLoginService->handleFacebookCallback();
    }
}
