<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah.',
                ])
                ->onlyInput('email');
        }
        $user = Auth::user();

        if ($user->is_active === false) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Akun ini sedang tidak aktif.',
                ]);
        }

        $user->loadMissing('shift');

        $shiftRoles = ['kasir', 'dapur'];

        if (in_array($user->role, $shiftRoles) && $user->shift && ! $user->shift->isActiveAt()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Akun ini hanya bisa digunakan sesuai jadwal shift.',
                ]);
        }

        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        }

        if ($user->role === 'kasir') {
            return redirect()->route('kasir.dashboard');
        }

        if ($user->role === 'dapur') {
            return redirect()->route('dapur.dashboard');
        }

        // if ($user->role === 'owner') {
        //     return redirect()->route('owner.dashboard');
        // }

        Auth::logout();

        return redirect()
            ->route('login')
            ->withErrors([
                'email' => 'Role akun tidak dikenali.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
