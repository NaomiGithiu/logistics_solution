@extends('layouts.app')

@section('content')

    <div class="container_fluid mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Driver Earnings Report</h5>
                <h5 class="mb-0">Total Earnings: {{$totalEarnings}}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>Driver Name</th>
                                <th>Total Earnings (KES)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($driverEarnings as $earning)
                                <tr>
                                    <td>{{ $earning->driver->name ?? 'Unknown Driver' }}</td>
                                    <td class="font-weight-bold text-success">
                                        KES {{ number_format($earning->total_earnings, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
                    @foreach($completedBookings as $booking)
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
                    @foreach($canceledBookings as $canceledbooking)
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