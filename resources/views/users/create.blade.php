@extends('layouts.app')

@section('content')
<div class="container_fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Create User</div>
                <div class="card-body">
                    <form action="{{ url('users') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{old('name')}}">
                            
                            @error('name')
                                <div style='color:red'>
                                        {{$message}}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control" maxlength="10" 
                            pattern="0\d{9}" placeholder="Enter phone number e.g. 0712345678" value="{{old('phone_number')}} ">

                            @error('phone_number')
                                <div style='color:red'>
                                        {{$message}}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{old('email')}}">

                            @error('email')
                                <div style='color:red'>
                                        {{$message}}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" class="form-select form-control" >
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>

                            @error('role')
                                <div style='color:red'>
                                        {{$message}}
                                </div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Create User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
