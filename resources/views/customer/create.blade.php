@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="card shadow p-4" style="max-width: 500px; width: 100%;">
        <h2 class="text-center mb-4">Book a Ride</h2>

        <div style="color: red">
            @if (session('error'))
            {{session('error')}}
            @endif
        </div>

        <form action="{{ route('bookings.create') }}" method="POST">
            @csrf
            <input type="hidden" name="customer_id" value="{{ auth()->id() }}">
            {{-- <input type="hidden" name="booking_id" value="{{ $booking->id }}"> --}}


            <!-- Pickup Location -->
            <div class="mb-3">
                <label for="pickup_location" class="form-label">Pickup Location</label>
                <input type="text" id="pickup_location" name="pickup_location" class="form-control" required>
                @error('pickup_location')
                    <div class="alert alert-danger mt-2 p-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Dropoff Location -->
            <div class="mb-3">
                <label for="dropoff_location" class="form-label">Dropoff Location</label>
                <input type="text" id="dropoff_location" name="dropoff_location" class="form-control" required>
                @error('dropoff_location')
                    <div class="alert alert-danger mt-2 p-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Vehicle Type -->
            <div class="mb-3">
                <label for="vehicle_type" class="form-label">Vehicle Type</label>
                <select id="vehicle_type" name="vehicle_type" class="form-select form-control">
                    <option value="bike">Bike</option>
                    <option value="van">Van</option>
                    <option value="truck">Truck</option>
                </select>
                @error('vehicle_type')
                    <div class="alert alert-danger mt-2 p-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Scheduled Ride -->
            <div class="mb-3">
                <label for="scheduled_time" class="form-label">Schedule Ride</label>
                <input type="datetime-local" id="scheduled_time" name="scheduled_time" class="form-control">
            </div>

            <!-- Confirm Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Confirm Booking</button>
            </div>
        </form>
    </div>
</div>
@endsection
