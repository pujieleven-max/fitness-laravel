<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (! $user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda dinonaktifkan oleh Administrator / Owner.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            // Track user login activity
            ActivityLog::record(
                'LOGIN',
                'Autentikasi',
                "Pengguna {$user->name} ({$user->role}) berhasil masuk ke sistem.",
                $user,
                null,
                ['email' => $user->email, 'role' => $user->role]
            );

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'Kredensial yang dimasukkan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            // Track user logout activity
            ActivityLog::record(
                'LOGOUT',
                'Autentikasi',
                "Pengguna {$user->name} ({$user->role}) keluar dari sistem.",
                $user
            );
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar (logout).');
    }
}
