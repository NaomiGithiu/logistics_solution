@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="card shadow p-4" style="max-width: 600px; width: 100%;">
        <h2 class="text-center mb-4">Booking Details</h2>

        <div class="booking-info">
            <p><strong>Pickup Location:</strong> {{ $booking->pickup_location }}</p>
            <p><strong>Dropoff Location:</strong> {{ $booking->dropoff_location }}</p>
            <p><strong>Vehicle Type:</strong> {{ ucfirst($booking->vehicle_type) }}</p>
            <p><strong>Scheduled Time:</strong> {{ \Carbon\Carbon::parse($booking->scheduled_time)->format('M d, Y - h:i A') }}</p>
            <p><strong>Driver:</strong> {{ $booking->driver->name ?? 'Not Assigned Yet' }}</p>  
            <p><strong>Estimated Fare:</strong> ksh.{{ number_format($booking->estimated_fare, 2) }}</p>
            <p><strong>Status:</strong> 
                <span class="badge 
                    @if($booking->status == 'pending') bg-warning 
                    @elseif($booking->status == 'in_progress') bg-primary 
                    @elseif($booking->status == 'delivered') bg-success 
                    @elseif($booking->status == 'canceled') bg-danger 
                    @endif">
                    {{ ucfirst($booking->status) }}
                </span>
            </p>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('bookings.create') }}" class="btn btn-success">Book Another Ride</a>

            @if($booking->status == 'pending')
                <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this ride?');">
                        Cancel Ride
                    </button>
                </form>

                @if(auth()->user()->role === 'admin')
                    <form action="{{ route('trips.assign', $booking->id) }}" method="POST" class="d-inline">
                        @csrf
                        <div class="input-group">
                            <label class="input-group-text" for="driver">Assign Driver</label>
                            <select name="driver_id" class="form-select" required>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Assign</button>
                        </div>
                    </form>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
