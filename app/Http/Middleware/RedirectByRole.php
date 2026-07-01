<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role === 'admin') {
            return redirect()->route('dashboard');
        }

        if (Auth::user()->role === 'kasir') {
            return redirect()->route('kasir.dashboard');
        }

        if (Auth::user()->role === 'dapur') {
            return redirect()->route('dapur.dashboard');
        }

        if (Auth::user()->role === 'owner') {
            return redirect()->route('owner.dashboard');
        }

        abort(403, 'Role tidak dikenali.');
    }
}
