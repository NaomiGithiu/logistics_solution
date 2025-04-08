@extends('layouts.admin')

@section('content')
    <h1>Manage Permissions</h1>
    
    <!-- Form for adding a new permission -->
    <form action="{{ route('admin.permissions.store') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Permission Name" required>
        <button type="submit">Add Permission</button>
    </form>

    <h2>Existing Permissions</h2>
    <ul>
        @foreach($permissions as $permission)
            <li>
                {{ $permission->name }}
                <a href="{{ route('admin.permissions.edit', $permission->id) }}">Edit</a> | 
                <form action="{{ route('admin.permissions.delete', $permission->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
