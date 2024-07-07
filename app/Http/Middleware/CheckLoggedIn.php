<?php

namespace App\Http\Middleware;

use Closure;
// use Illuminate\Http\Request;

class CheckLoggedIn
{
    public function handle($request, Closure $next)
    {
        if (session()->has('user')) {
            $user = session('user');
            if ($user['roles'] === 'pegawai') {
                // Redirect only if not already on the dashboard route
                if ($request->route()->getName() !== 'dashboard') {
                    return redirect()->route('dashboard');
                }
            }
        }

        return $next($request);
    }

}
