@extends('layouts.app')

@section('content')
<div class="container_fluid">
    <div class="card shadow p-3">
        <h4 class="mb-3 text-center">✅ My Completed Rides</h4>

        <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered text-center">
                <thead class="table-success">
                    <tr>
                        <th>Pickup</th>
                        <th>Dropoff</th>
                        <th>Vehicle</th>
                        <th>Weight(kg)</th>
                        <th>Price</th>
                        <th>Completed At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($completedBookings as $booking)
                    <tr>
                        <td>{{ $booking->pickup_location }}</td>
                        <td>{{ $booking->dropoff_location }}</td>
                        <td class="text-capitalize">{{ $booking->vehicle_type }}</td>
                        <td>{{ $booking->weight }}</td>
                        <td>{{ number_format($booking->estimated_fare, 2) }}</td:>
                        <td>{{ $booking->updated_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    @endforeach

                    @if($completedBookings->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center">No completed rides found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
