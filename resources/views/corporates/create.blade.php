@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Corporate Company</h2>
    <form action="{{ route('corporates.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Company Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        {{-- Removed corporate_id input --}}

        <div class="form-group">
            <label for="corporate_email">Corporate Email</label>
            <input type="email" name="corporate_email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="contact_person">Contact Person</label>
            <input type="text" name="contact_person" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="contact">Contact</label>
            <input type="text" name="contact" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input name="address" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Create</button>
        <a href="{{ route('corporates.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
