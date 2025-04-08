@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="card shadow-lg mx-auto p-4" style="max-width: 600px;">

        @if(session('change_password'))
            <div class="alert alert-warning">
                {{ session('change_password') }}
            </div>
        @endif

        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">Edit Profile</h2>
        </div>

        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Full Name -->
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                           class="form-control" required>
                </div>

                <!-- Email (Disabled) -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                           class="form-control bg-light" disabled>
                </div>

                <!-- Phone Number -->
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $user->Phone_number) }}" 
                           class="form-control">
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="d-flex align-items-center">
                        <input type="password" value="••••••••" class="form-control me-2" disabled>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="togglePasswordForm()">Change Password</button>
                    </div>
                </div>

                <!-- Toggleable Change Password Form -->
                <div id="passwordForm" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control">
                    </div>
                </div>


                <!-- Profile Picture Upload -->
                <div class="mb-3">
                    <label class="form-label">Profile Picture</label>
                    <input type="file" name="profile_image" class="form-control">
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePasswordForm() {
        const form = document.getElementById('passwordForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
</script>
@endsection
