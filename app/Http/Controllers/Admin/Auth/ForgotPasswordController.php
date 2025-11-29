<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    public function create()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return Inertia::render('Admin/Auth/ForgotPassword');
    }

    public function store(Request $request)
    {
        // if (Auth::check()) {
        //     return redirect()->route('admin.dashboard');
        // }
        $request->validate(['email' => 'required|email|exists:users']);
        Password::sendResetLink($request->only('email'));
        return back()->with('status', 'Password reset link sent!');
    }
}

