<?php

namespace App\Http\Middleware;
use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Role
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
        $user = auth()->user();
        if($user) {
            $user = User::find($user->id);
            $currentRole =  $user->hasAnyRole(['administrator','manager', 'staff', 'b2c', 'accounts','marketing']);
            if ($currentRole) {
                return $next($request);
            }
            Auth::logout();
        }
    }
}
