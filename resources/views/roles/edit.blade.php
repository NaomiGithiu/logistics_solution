@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Role</h2>
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Role Name</label>
            <input type="text" name="name" value="{{ $role->name }}" class="form-control" required>
        </div>

        <h5>Update Permissions</h5>
        @foreach($permissions as $permission)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                    {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                <label class="form-check-label">{{ $permission->name }}</label>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary mt-3">Update Role</button>
    </form>
</div>
@endsection
