@extends('layouts.app')

@section('content')
<div class="container_fluid">
    <div class="card shadow p-3">
        <h4 class="mb-3 text-center">📌 My Booking Requests</h4>

        <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered text-center">
                <thead class="table-primary">
                    <tr>
                        <th>Pickup</th>
                        <th>Dropoff</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->pickup_location }}</td>
                        <td>{{ $booking->dropoff_location }}</td>
                        <td class="text-capitalize">{{ $booking->vehicle_type }}</td>
                        <td>
                            @if($booking->status == 'pending')
                                <span class="badge bg-warning color-light">Pending</span>
                            @elseif($booking->status == 'confirmed')
                                <span class="badge bg-success">Confirmed</span>
                            @elseif($booking->status == 'completed')
                                <span class="badge bg-primary">Completed</span>
                            @elseif($booking->status == 'canceled')
                                <span class="badge bg-danger">Canceled</span>
                            @endif
                        </td>
                        <td>
                            @if($booking->status == 'pending')
                                <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-danger">❌ Cancel</button>
                                </form>
                            @else
                                <span class="text-muted">No actions</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                    @if($bookings->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center">No booking requests found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
