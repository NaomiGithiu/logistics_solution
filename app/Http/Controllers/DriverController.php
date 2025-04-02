<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DriverController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view ('users.index')->with('users', $users);
    }

    public function create()
    {
        $roles = Role::all(); // Fetch all roles
        return view('users.create', compact('roles'));
    }
    

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt('password'),
        ]);

        //$user->roles()->attach($request->role);

        return redirect('users');
    }

    public function show($id)
    {
       $user = User::find($id);
       return view('users.show')-> with('users', $user);
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
        return redirect('users')->with('flash_message', 'user updated' );
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect('users')->with('flash_message', 'user deleted');
    }
}
