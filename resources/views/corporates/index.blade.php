@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Corporate Companies</h2>
        <a href="{{ route('corporates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Company
        </a>
    </div>

    @if(session('flash_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('flash_message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Name</th>
                    <th>Corporate ID</th>
                    <th>Email</th>
                    <th>Contact Person</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($corporates as $corporate)
                    <tr>
                        <td>{{ $corporate->name }}</td>
                        <td>{{ $corporate->corporate_id }}</td>
                        <td>{{ $corporate->corporate_email }}</td>
                        <td>{{ $corporate->contact_person }}</td>
                        <td>{{ $corporate->contact }}</td>
                        <td>{{ $corporate->address }}</td>
                        <td class="text-center">
                            <a href="{{ route('corporates.show', $corporate->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('corporates.edit', $corporate->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <a href="{{ route('corporates.addAdminForm', $corporate->id) }}" class="btn btn-secondary btn-sm">Add Admin</a>
                            <form action="{{ route('corporates.destroy', $corporate->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this company?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No corporate companies found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
