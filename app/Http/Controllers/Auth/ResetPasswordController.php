<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;

class ResetPasswordController extends Controller
{

public function showResetForm($token) {
    return view('auth.reset-password', ['token' => $token]);
}

public function reset(Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|confirmed|min:6',
        'token' => 'required'
    ]);

    $record = DB::table('password_resets')->where([
        'email' => $request->email,
        'token' => $request->token,
    ])->first();

    if (!$record || Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
        return back()->withErrors(['email' => 'Invalid or expired token.']);
    }

    // Update user password
    User::where('email', $request->email)->update([
        'password' => Hash::make($request->password)
    ]);

    DB::table('password_resets')->where('email', $request->email)->delete();

    return redirect('/login')->with('status', 'Password has been reset.');
}

}
