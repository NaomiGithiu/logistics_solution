@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 text-center text-primary">Earnings Summary</h2>

    <!-- Total Earnings Card -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Total Earnings</h5>
            <p class="card-text">Ksh <strong>{{ number_format($totalEarnings, 2) }}</strong></p>
        </div>
    </div>

    <!-- Completed Trips Table -->
    <h4 class="mt-4 mb-3">Completed Trips</h4>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Trip ID</th>
                    <th>Pickup Location</th>
                    <th>Dropoff Location</th>
                    <th>Weight (kg)</th>
                    <th>Fare (Ksh)</th>
                    <th>Completed On</th>
                </tr>
            </thead>
            <tbody>
                @foreach($completedBookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>{{ $booking->pickup_location }}</td>
                        <td>{{ $booking->dropoff_location }}</td>
                        <td>{{ $booking->weight }}</td>
                        <td>Ksh {{ number_format($booking->estimated_fare, 2) }}</td>
                        <td>{{ $booking->updated_at->format('d M Y, H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
