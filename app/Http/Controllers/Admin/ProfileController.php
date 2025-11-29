<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return inertia('Admin/Profile/Edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'old_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
            'image' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->name;

        // Handle password change
        if ($request->filled('old_password') && $request->filled('new_password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Old password is incorrect.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $user->image = $request->file('image')->store('images/avatars', 'public');
        }

        $user->save();
        return redirect()->route('admin.profile')->with('status', 'Profile updated successfully.');
    }
}
