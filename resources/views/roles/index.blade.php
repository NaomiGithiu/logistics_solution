@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Roles</h2>
    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Add Role</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <ul class="list-group">
        @foreach($roles as $role)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $role->name }}
                <div>
                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this role?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@endsection
