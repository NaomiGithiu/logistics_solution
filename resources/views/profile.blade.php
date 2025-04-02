@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="card shadow-lg p-4" style="max-width: 600px; margin: auto;">
        <div class="text-center">
            <h2 class="mb-3">Profile Management</h2>
        </div>

        <div class="d-flex align-items-center flex-column">
            <div class="profile-img mb-3">
                <img src="{{ asset('storage/profile/' . $user->profile_image) }}" 
                     alt="Profile Picture" 
                     class="rounded-circle border border-2" 
                     style="width: 120px; height: 120px; object-fit: cover;">
            </div>

            <div class="text-center">
                <h3 class="mb-1">{{ $user->name }}</h3>
                <p class="text-muted">{{ $user->email }}</p>
                <p class="text-muted">Joined: {{ $user->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        <hr>

        <div class="user-details">
            <h4 class="mb-3">User Details</h4>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Phone:</strong> {{ $user->Phone_number ?? 'Not Set' }}</li>
                <li class="list-group-item"><strong>Address:</strong> {{ $user->address ?? 'Not Provided' }}</li>
                <li class="list-group-item"><strong>Role:</strong> <span class="badge bg-primary">{{ ucfirst($user->getRoleNames()->first()) }}</span></li>
            </ul>
        </div>

        <div class="d-flex justify-content-center mt-4">
            <a href="{{ route('profile.edit') }}" class="btn btn-success px-4">Edit Profile</a>
        </div>
    </div>
</div>
@endsection