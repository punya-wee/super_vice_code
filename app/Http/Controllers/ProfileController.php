<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;

class ProfileController extends Controller
{
    /**
     * Show user profile
     */
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new UserProfile();
        
        return view('profile', [
            'user' => $user,
            'profile' => $profile
        ]);
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate all inputs
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id . ',id',
            'avatar_url' => 'nullable|url',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        // Update users table
        $user->update([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
        ]);

        // Update or create user_profile
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'avatar_url' => $validated['avatar_url'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]
        );

        return redirect()->route('profile.show')->with('status', 'อัพเดตโปรไฟล์สำเร็จ!');
    }
}

