@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow rounded">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Bulk Booking Approval</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('bookings.bulk-approve') }}">
                @csrf

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Select</th>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Pickup</th>
                                <th>Dropoff</th>
                                <th>Scheduled Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingBookings as $booking)
                                <tr>
                                    <td><input type="checkbox" name="trip_ids[]" value="{{ $booking->id }}"></td>
                                    <td>{{ $booking->id }}</td>
                                    <td>{{ $booking->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->pickup_location }}</td>
                                    <td>{{ $booking->dropoff_location }}</td>
                                    <td>{{ $booking->scheduled_time }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No pending bookings available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <button type="submit" class="btn btn-primary">Approve Selected</button>
            </form>
        </div>
    </div>
</div>
@endsection
