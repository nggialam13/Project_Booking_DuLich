<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class RedirectDeletedUser
{
    public function handle($request, Closure $next)
    {
        $userId = session('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

        if ($userId && !User::where('id', $userId)->exists()) {

            session()->forget('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

            return redirect()
                ->route('login')
                ->with('error', 'Tài khoản của bạn đã bị xóa khỏi hệ thống.');
        }

        return $next($request);
    }
}