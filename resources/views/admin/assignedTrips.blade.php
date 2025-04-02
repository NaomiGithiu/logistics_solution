@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow p-3">
        <h4 class="mb-3 text-center">🚖 Assigned Trips</h4>

        <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered text-center align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Pickup</th>
                        <th>Dropoff</th>
                        <th>Vehicle</th>
                        <th>Weight(kg)</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->pickup_location }}</td>
                        <td>{{ $booking->dropoff_location }}</td>
                        <td class="text-capitalize">{{ $booking->vehicle_type }}</td>
                        <td>{{ $booking->weight }}</td>
                        <td>{{ number_format($booking->estimated_fare, 2) }}</td:>
                        <td>
                            <div class="d-flex gap-2">
                                <!-- Complete Trip Button -->
                                <form action="{{ route('trips.complete', $booking->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success">✅ Complete</button>
                                </form>

                                <!-- Cancel Trip Form -->
                                {{-- <form action="{{ route('trips.cancel', $booking->id) }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="cancel_reason" class="form-control form-control-sm w-auto" placeholder="Reason" required>
                                    <button type="submit" class="btn btn-sm btn-danger">❌ Cancel</button>
                                </form> --}}
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if($bookings->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center">No accepted trips available.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
