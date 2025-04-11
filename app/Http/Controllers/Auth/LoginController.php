<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\VerifyToken;
use App\Mail\welcomeMail;
use App\Mail\User;
use illuminate\Support\Facades\Log;


class LoginController extends Controller
{
    public function showLoginForm(){
       return view('auth.login');
    }

    public function store(Request $request){
       
       
        //user validation
       $validateData = $request -> validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = Auth::attempt($request->only('email', 'password'));

    if (Auth::check()) {
        $validToken = random_int(1000, 9999); 
        $get_token = new VerifyToken();
        $get_token->token = $validToken;
        $get_token->email = $request['email'];
        $get_token->save();

        
        $user = Auth::user();
        Log::info("OTP for {$user->email}:{$validToken}");
 
        Mail::to($request['email'])->send(new welcomeMail($request['email'], $validToken, $user->name));
    

        return redirect()->route('verifyaccount')->with('message', 'Please verify your account');
        } else {
            return back()->with('status', 'invalid login');
        }

        if (!@auth() -> attempt($request -> only('email', 'password'))){
            return back()->with('status', 'invalid login');
          }

   }

    //loging out the user
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
    
}



