@extends('layouts.app')

@section('content')
<div class="container_fluid">
    <h2>Add Corporate Admin</h2>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Corporate Admin</div>
                <div class="card-body">
                    <form action="{{ route('corporates.addAdmin', $corporate->id) }}" method="POST">
                        @csrf

                        <!-- Hidden corporate_id field (auto-filled in controller) -->
                        <input type="hidden" name="corporate_id" value="{{ $corporate->id }}">

                        <div class="mb-3">
                            <label for="name" class="form-label">Admin Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                            
                            @error('name')
                                <div style='color:red'>
                                    {{$message}}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control" maxlength="10" 
                            pattern="0\d{9}" placeholder="Enter phone number e.g. 0712345678" value="{{ old('phone_number') }}" required>

                            @error('phone_number')
                                <div style='color:red'>
                                    {{$message}}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Admin Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                            
                            @error('email')
                                <div style='color:red'>
                                    {{$message}}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" class="form-select form-control" required>
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

                        <!-- This field is automatically set to true, no need for it in the form -->
                        <input type="hidden" name="is_corporate_admin" value="1">

                        <div class="mb-3">
                            <button type="submit" class="btn btn-success">Add Admin</button>
                            <a href="{{ route('corporates.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
