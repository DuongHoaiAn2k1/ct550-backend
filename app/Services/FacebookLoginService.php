<?php

namespace App\Services;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class FacebookLoginService
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->scopes('email')->stateless()->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();
            $user = User::where('facebook_id', $facebookUser->getId())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    'facebook_id' => $facebookUser->getId(),
                    'password' => bcrypt(str_random(16)), // Generate a random password
                ]);
            }

            $token = JWTAuth::fromUser($user);
            $refreshToken = $this->createRefreshToken($user->id);

            return $this->respondWithToken($token, $refreshToken, $user->id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not authenticate with Facebook', 'message' => $e->getMessage()], 500);
        }
    }

    protected function respondWithToken($token, $refreshToken, $userId)
    {
        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'user_id' => $userId,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    private function createRefreshToken($userId)
    {
        $data = [
            'user_id' => $userId,
            'random' => rand() . time(),
            'exp' => time() + config('jwt.refresh_ttl')
        ];

        $refreshToken = JWTAuth::getJWTProvider()->encode($data);
        return $refreshToken;
    }
}
