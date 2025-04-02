@extends('layouts.app')

@section('content')
<div class="container_fluid">
    <h2 class="mb-4">Earnings Summary</h2>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Total Earnings: <strong>Ksh {{ number_format($totalEarnings, 2) }}</strong></h5>
        </div>
    </div>

    <h4 class="mt-4">Completed Trips</h4>
    <table class="table table-striped">
        <thead>
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
@endsection
