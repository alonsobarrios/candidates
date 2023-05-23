<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->only(['username', 'password']);

            if (!$token = auth()->attempt($credentials)) {
                return response()->json([
                    'meta' => [
                        'success' => false,
                        'errors' => [
                            "Password incorrect for: {$credentials['username']}"
                        ]
                    ]
                ], 401);
            }

            return $this->respondWithToken($token);

        } catch (\Exception $e) {
            Log::error('Authentication error: ' . $e->getMessage());
            return response()->json([
                'meta' => [
                    'success' => false,
                    'errors' => ['Authentication error.']
                ]
            ], 500);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'meta' => [
                'success' => true,
                'errors' => []
            ],
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'minutes_to_expire' => auth()->factory()->getTTL()
            ]
        ]);
    }
}
