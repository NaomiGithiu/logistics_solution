@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="card shadow-sm mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <h5 class="card-title text-center mb-4">Edit User</h5>

            <form action="{{ url('users/' . $users->id) }}" method="POST">
                @csrf
                @method("PATCH")

                <!-- Name Field -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" placeholder="Enter name" 
                        class="form-control @error('name') is-invalid @enderror" 
                        value="{{ $users->name }}">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Phone Number Field -->
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" placeholder="Enter phone number" 
                        class="form-control @error('phone_number') is-invalid @enderror" 
                        value="{{ $users->Phone_number }}">
                    @error('phone_number')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        value="{{ $users->email }}">
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
