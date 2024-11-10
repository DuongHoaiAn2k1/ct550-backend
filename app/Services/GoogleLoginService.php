<?php

namespace App\Services;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use App\Events\User\UserRegistered;

class GoogleLoginService
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->with(['prompt' => 'select_account'])->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            \Log::info('Google User: ' . json_encode($googleUser));

            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => bcrypt(Str::random(16)),
                ]);
                $user->assignRole('normal_user');

                event(new UserRegistered());
            }

            $token = auth()->login($user);
            $refreshToken = $this->createRefreshToken($user);

            $roles = $user->getRoleNames();

            $affiliateRole = in_array('affiliate_marketer', $roles->toArray()) ? 'affiliate_marketer' : null;

            $mainRole = $roles->filter(function ($role) {
                return $role !== 'affiliate_marketer';
            })->first();

            echo "<script>
                window.opener.postMessage({
                  access_token: '$token',
                  refresh_token: '$refreshToken',
                  user_id: '$user->id',
                  email: '$user->email',
                  role: '" . base64_encode($mainRole) . "',
                  affiliate_role: '$affiliateRole',
                  google_id: '$user->google_id'
                }, '" . env('CLIENT_URL') . "');
                window.close();
              </script>";
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to authenticate with Google', 'message' => $e->getMessage()], 500);
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

    private function createRefreshToken($user)
    {
        $data = [
            'user_id' => $user->id,
            'random' => rand() . time(),
            'exp' => time() + config('jwt.refresh_ttl')
        ];

        $refreshToken = JWTAuth::getJWTProvider()->encode($data);
        return $refreshToken;
    }
}
