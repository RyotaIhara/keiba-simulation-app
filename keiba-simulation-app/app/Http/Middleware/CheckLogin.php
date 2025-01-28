<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\General\AuthGeneral;

class CheckLogin
{
    public function handle(Request $request, Closure $next)
    {
        // ログインしていない場合、リダイレクト
        $authGeneral = new AuthGeneral();
        if (!$authGeneral->isLogin()) {
            return redirect('/login')->with('success', 'ログインしてください');;
        }

        return $next($request);
    }
}

