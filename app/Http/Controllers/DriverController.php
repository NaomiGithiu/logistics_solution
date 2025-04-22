<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Mail\SetPasswordMail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;  // Import the Str facade for random string generation
use App\Http\Requests\StoreUserRequest;
use illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Corporate_Companies;

class DriverController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->corporate_id; 
        $users = User::where('corporate_id', $userId)
                            ->with('roles')
                            ->get();  

          
        // $users = User::with('roles')->get();
        $role = Role::all();
        return view('users.index', compact('role'))->with('users', $users);
    }

    public function create()
    {
        $roles = Role::all(); // Fetch all roles
        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $randomPassword = Str::random(10);

        $corporate_id = auth()->user()->corporate_id;

        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($randomPassword),
            'corporate_id' => $corporate_id, // or corporate->corporate_id if that's the right one
            'must_change_password' => true,
        ]);

        Log::info("password for {$user->email}: {$randomPassword}");

        Mail::to($user->email)->send(new SetPasswordMail($user, $randomPassword));

        return redirect('users');
    }


    public function show($id)
    {
        $user = User::find($id);
        return view('users.show')->with('users', $user);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('users.edit')->with('users', $user);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $input = $request->all();
        $user->update($input);
        return redirect('users')->with('flash_message', 'user updated');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); // Soft delete the user

        return redirect('users')->with('flash_message', 'User soft deleted');
    }
}
