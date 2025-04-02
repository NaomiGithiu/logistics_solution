@extends('layouts.app')

@section('content')

<div class="container_fluid">
@foreach ($bookings as $booking)
    <div class="card mb-3">
        <div class="card-header">
            <h5>Booking #{{ $booking->id }}</h5>
        </div>
        <div class="card-body">
            <!-- Display pickup and dropoff locations -->
            <p><strong>Pickup Location:</strong> {{ $booking->pickup_location }}</p>
            <p><strong>Dropoff Location:</strong> {{ $booking->dropoff_location }}</p>
            
            <!-- Admin Inputs for weight and driver assignment -->
            <form action="{{ route('admin.updateBooking', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <!-- Input for weight -->
                    <label for="weight" class="form-label">Weight (kg):</label>
                    <input type="number" name="weight" value="{{ old('weight', $booking->weight) }}" class="form-control" required min="1">
                </div>
                
                <div class="mb-3">
                    <!-- Dropdown for driver assignment -->
                    <label for="driver_id" class="form-label">Assign Driver:</label>
                    <select name="driver_id" class="form-select" required>
                        <option value="" disabled selected>Select Driver</option>
                        @foreach ($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ $driver->id == old('driver_id', $booking->driver_id) ? 'selected' : '' }}>
                                {{ $driver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Booking</button>
            </form>
        </div>
    </div>
@endforeach
</div>
@endsection
