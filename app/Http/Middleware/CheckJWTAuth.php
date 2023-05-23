<?php

namespace App\Http\Middleware;

use Closure;

class CheckJWTAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return $this->exceptionResponseStructure('Not authorized.');
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $ex) {
            return $this->exceptionResponseStructure('Token expired.');
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $exc) {
            return $this->exceptionResponseStructure($exc->getMessage(), $exc->getCode());
        }

        return $next($request);
    }

    private function exceptionResponseStructure($message, $code = 401)
    {
        return response()->json([
            'meta' => [
                'success' => false,
                'errors' => [$message]
            ]
        ], $code);
    }
}
