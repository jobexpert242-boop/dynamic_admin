<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return Inertia::render('Admin/Auth/Login');
    }

    // public function Adminlogin(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'login' => 'required|string',
    //         'password' => 'required|string',
    //     ]);

    //     $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

    //     if (Auth::attempt([$loginType => $credentials['login'], 'password' => $credentials['password']])) {
    //         $request->session()->regenerate();
    //         return redirect()->intended('/admin/dashboard')->with('status', 'Login Successfull!');
    //     }

    //     return back()->withErrors([
    //         'login' => 'The provided credentials do not match our records.',
    //     ]);
    // }
    public function Adminlogin(Request $request)
    {
        $credentials = $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $remember  = $request->boolean('remember');

        if (Auth::attempt([$loginType => $credentials['login'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();
            if ($remember) {
                $user = Auth::user();
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
            return redirect()->intended('/admin/dashboard')->with('status', 'Login Successful!');
        }

        return back()->withErrors([
            'error' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login')->with('status', 'Logout Successfull!');
    }
}
