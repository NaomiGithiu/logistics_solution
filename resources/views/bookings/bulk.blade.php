@extends('layouts.app')

@section('content')

<div class="container-fluid d-flex justify-content-center">
    <div class="card shadow p-4" style="max-width: 500px; width: 100%;">
        <h2 class="text-center mb-4">Bulk Ride Booking</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="mb-3 text-center">
            <a href="{{ route('bookings.template') }}" class="btn btn-outline-info">Download Excel Template</a>
        </div>

        <form action="{{ route('bookings.bulkUpload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="customer_id" value="{{ auth()->id() }}">

            <div class="mb-3">
                <label for="file" class="form-label">Upload Excel File</label>
                <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Upload Bookings</button>
            </div>
        </form>
    </div>
</div>

@endsection
