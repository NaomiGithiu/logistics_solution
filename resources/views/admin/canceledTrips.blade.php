
@extends('layouts.app')

@section('content')
<div class="container_fluid">
    <h4 class="mb-3">🚖 Canceled Trips (Driver Canceled Only)</h4>

    <div class="table-responsive">
        <table class="table table-hover table-bordered text-center">
            <thead class="table-danger">
                <tr>
                    <th>Pickup</th>
                    <th>Dropoff</th>
                    <th>Vehicle</th>
                    <th>Weight(kg)</th>
                    <th>Price</th>
                    <th>Cancel Reason</th>
                    <th>Reassign Driver</th>
                </tr>
            </thead>
            <tbody>
                @foreach($canceledBookings as $booking)
                <tr>
                    <td>{{ $booking->pickup_location }}</td>
                    <td>{{ $booking->dropoff_location }}</td>
                    <td class="text-capitalize">{{ $booking->vehicle_type }}</td>
                    <td>{{ $booking->weight }}</td>
                    <td>{{ number_format($booking->estimated_fare, 2) }}</td>
                    <td>{{ $booking->cancel_reason }}</td>
                    <td>
                        <form action="{{ route('admin.reassignDriver', $booking->id) }}" method="POST">
                            @csrf
                            <div class="d-flex gap-2">
                                <select name="driver_id" class="form-select form-select-sm form-control" required>
                                    <option value="">Select Driver</option>
                                    @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Reassign</button>
                            </div>
                        </form>
                    </td>
                </tr>
                @endforeach

                @if($canceledBookings->isEmpty())
                <tr>
                    <td colspan="7" class="text-center">No canceled trips available.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
