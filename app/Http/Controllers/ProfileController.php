<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    public function profile()
    {
        $user = Auth::user(); // Get the logged-in user
        return view('profile', compact('user'));
    }

        public function editProfile()
    {
        $user = Auth::user(); // Get logged-in user
        return view('profile_edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Handle Profile Image Upload
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::delete('public/profile/' . $user->profile_image);
            }
            $imageName = time() . '.' . $request->profile_image->extension();
            $request->profile_image->storeAs('public/profile', $imageName);
            $user->profile_image = $imageName;
        }

        // Update User Information
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }

    }

