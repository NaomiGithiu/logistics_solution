@extends('layouts.app')

@section('content')

    <div class="container_fluid mt-4">

        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-success">Trip Statistics</h6>
            </div>
            <div class="card-body">
                <p>Total Trips: {{$totalTrips}}</p>
                <p>Total Distance: 150 km</p>
                <p>Total Fare: {{$totalFare}}</p>
            </div>
        </div>

        <h4 class="mt-4 text-secondary">Completed Trips</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle shadow-sm">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Trip ID</th>
                        <th>Pickup Location</th>
                        <th>Dropoff Location</th>
                        <th>Weight (kg)</th>
                        <th>Fare (Ksh)</th>
                        <th>Completed On</th>
                    </tr>
                </thead>
                <tbody class="table-light">
                    @foreach($completedbookings as $booking)
                        <tr>
                            <td class="text-center fw-bold">{{ $booking->id }}</td>
                            <td>{{ $booking->pickup_location }}</td>
                            <td>{{ $booking->dropoff_location }}</td>
                            <td class="text-center">{{ $booking->weight }}</td>
                            <td class="text-success fw-bold"> {{ number_format($booking->estimated_fare, 2) }}</td>
                            <td class="text-center">{{ $booking->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>                            
        </div>

        <h4 class="mt-4 text-secondary">Canceled Trips</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle shadow-sm">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Trip ID</th>
                        <th>Pickup Location</th>
                        <th>Dropoff Location</th>
                        <th>Weight (kg)</th>
                        <th>Fare (Ksh)</th>
                        <th>Canceled On</th>
                    </tr>
                </thead>
                <tbody class="table-light">
                    @foreach($canceledbookings as $canceledbooking)
                        <tr>
                            <td class="text-center fw-bold">{{ $booking->id }}</td>
                            <td>{{ $canceledbooking->pickup_location }}</td>
                            <td>{{ $canceledbooking->dropoff_location }}</td>
                            <td class="text-center">{{ $canceledbooking->weight }}</td>
                            <td class="text-success fw-bold"> {{ number_format($canceledbooking->estimated_fare, 2) }}</td>
                            <td class="text-center">{{ $canceledbooking->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>                            
        </div>
    </div>



@endsection