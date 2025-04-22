@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Pending Trip Approvals</h4>
        </div>
        <div class="card-body">
            @if($pendingTrips->isEmpty())
                <div class="alert alert-info">No pending trips for approval</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Pickup Location</th>
                                <th>Scheduled Time</th>
                                <th>Vehicle Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingTrips as $trip)
                            <tr>
                                <td>{{ $trip->id }}</td>
                                <td>{{ $trip->customer->name }}</td>
                                <td>{{ $trip->pickup_location }}</td>
                                <td>{{ $trip->scheduled_time->format('M d, Y H:i') }}</td>
                                <td>{{ ucfirst($trip->vehicle_type) }}</td>
                                <td>
                                    <a href="{{ route('bookings.show-for-approval', $trip->id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Review
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection