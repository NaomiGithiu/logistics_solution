@extends('layouts.app')

@section('content')
<div class="container_fluid">
    <div class="card shadow p-4">
        <h2 class="text-center mb-4">All Booking Details</h2>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Pickup Location</th>
                    <th>Dropoff Location</th>
                    <th>Vehicle Type</th>
                    <th>Scheduled Time</th>
                    <th>Driver</th>
                    <th>Estimated Fare</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $index => $booking)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $booking->pickup_location }}</td>
                    <td>{{ $booking->dropoff_location }}</td>
                    <td>{{ ucfirst($booking->vehicle_type) }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->scheduled_time)->format('M d, Y - h:i A') }}</td>
                    <td>{{ $booking->driver->name ?? 'Not Assigned Yet' }}</td>
                    <td>ksh.{{ number_format($booking->estimated_fare, 2) }}</td>
                    <td>
                        <span class="badge 
                            @if($booking->status == 'pending') bg-warning 
                            @elseif($booking->status == 'in_progress') bg-primary 
                            @elseif($booking->status == 'delivered') bg-success 
                            @elseif($booking->status == 'canceled') bg-danger 
                            @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>
                        @if($booking->status == 'pending')
                            <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this ride?');">Cancel</button>
                            </form>
                            @if(auth()->user()->role === 'admin')
                                <form action="{{ route('trips.assign', $booking->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <div class="input-group">
                                        <label class="input-group-text" for="driver">Assign Driver</label>
                                        <select name="driver_id" class="form-select form-select-sm" required>
                                            @foreach($drivers as $driver)
                                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">Assign</button>
                                    </div>
                                </form>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center mt-3">
            <a href="{{ route('bookings.create') }}" class="btn btn-success">Book Another Ride</a>
        </div>
    </div>
</div>
@endsection
