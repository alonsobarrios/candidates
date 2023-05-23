<?php

namespace App\Http\Middleware;

use Closure;

class CheckRoleAuth
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

            if ($user->role !== 'manager') {
                return $this->exceptionResponseStructure('Not authorized.');
            }
        } catch (\Exception $e) {
            return $this->exceptionResponseStructure('Not authorized.');
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

