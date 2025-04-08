<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerifyToken;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return view('home');
        $user = User::where('email', auth()->user()->email)->first();

        if ($user->is_activated == 1) {
        
            if ($user->role == '1') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role == '3') {
                return redirect()->route('driver.dashboard');
            } else {
                return redirect()->route('customer.dashboard'); // Default for customers
            } // Default for other users
        } else {
            return redirect()->route('verifyaccount');
        }
    }

    public function verifyaccount(){
        return view('otp-verification');
    }

    public function useractivation(Request $request)
    {
        $get_token = VerifyToken::where('token', $request->token)->first();

        if ($get_token) {
            $user = User::where('email', $get_token->email)->first();
            $user->is_activated = 1;
            $user->save();

            // Delete the token after use
            $get_token->delete();

            // Log in the user
            auth()->login($user);
            session()->regenerate();

            // ✅ Redirect to profile if password must be changed
            if ($user->must_change_password) {
                return redirect()->route('profile')->with('change_password', 'Please change your password before continuing.');
            }

            // Role-based redirection
            if ($user->role == '1') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role == '3') {
                return redirect()->route('driver.dashboard');
            } else {
                return redirect()->route('customer.dashboard');
            }

        } else {
            return redirect('/verifyaccount')->with('status', 'Invalid OTP');
        }
    }

    
}
