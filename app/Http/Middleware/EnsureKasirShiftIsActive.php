<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureKasirShiftIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user || ! in_array($user->role, ['kasir', 'dapur'], true)) {
            return $next($request);
        }

        $user->loadMissing('shift');

        if (! $user->shift || $user->shift->isActiveAt()) {
            return $next($request);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withErrors([
                'email' => 'Shift Anda sudah selesai. Silakan masuk kembali pada jadwal berikutnya.',
            ]);
    }
}
