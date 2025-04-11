<?php

namespace App\Http\Controllers;

use App\Models\Corporate_Companies;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str; 
use App\Http\Requests\StoreUserRequest;
use App\Mail\SetPasswordMail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;

class CorporateController extends Controller
{ public function index()
    {
        $corporates = Corporate_Companies::all();
        return view('corporates.index')->with('corporates', $corporates);
    }

    public function create()
    {
        $latest = Corporate_Companies::orderBy('id', 'desc')->first();
        $nextNumber = $latest ? ((int) filter_var($latest->corporate_id, FILTER_SANITIZE_NUMBER_INT)) + 1 : 1;
        $nextCorporateId = 'CP' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('corporates.create', compact('nextCorporateId'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'corporate_email' => 'required|email|unique:corporate_companies,corporate_email|max:255',
            'contact_person' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);


                    // genarating coorporate_id
            $latest = Corporate_Companies::orderBy('id', 'desc')->first();

            if ($latest && $latest->corporate_id) {
                $number = (int) filter_var($latest->corporate_id, FILTER_SANITIZE_NUMBER_INT);
                $nextNumber = $number + 1;
            } else {
                $nextNumber = 1;
            }

            $corporateId = 'CP' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

  
        $user = Corporate_Companies::create([
            'name' => $request->name,
            'corporate_id' => $corporateId,
            'corporate_email' => $request->corporate_email,
            'contact_person' => $request->contact_person,
            'contact' => $request->contact,
            'address' => $request->address
        ]);

        return redirect('corporates');
    }

    public function show($id)
    {
        $corporate = Corporate_Companies::find($id);
        return view('corporates.show')->with('corporates', $corporate);
    }

    public function edit($id)
    {
        $corporate = Corporate_Companies::find($id);
        return view('users.edit')->with('corporates', $corporate);
    }

    public function update(Request $request, $id)
    {
        $corporate = Corporate_Companies::find($id);
        $input = $request->all();
        $corporate->update($input);
        return redirect('corporates')->with('flash_message', 'corporate updated');
    }

    public function destroy($id)
    {
        $corporate = Corporate_Companies::findOrFail($id);
        $corporate->delete(); 

        return redirect('corporates')->with('flash_message', 'corporate soft deleted');
    }

    // adding admins

    public function addAdminForm($corporateId){

        $corporate = Corporate_Companies::findOrFail($corporateId);
        $roles = Role::all(); 

        return view('corporates.add_admin', compact('corporate', 'roles'));
    }
    public function addAdmin(StoreUserRequest $request, $corporateId)
    {
        $randomPassword = Str::random(10);

        $corporate = Corporate_Companies::findOrFail($corporateId);

        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($randomPassword),
            'corporate_id' => $corporate->corporate_id,
            'is_corporate_admin' => true,
            'must_change_password' => true,
        ]);

        Mail::to($user->email)->send(new SetPasswordMail($user, $randomPassword));

        return redirect('corporates')->with('flash_message', 'Corporate Admin created successfully.');
    }

}
