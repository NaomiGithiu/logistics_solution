@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="card shadow p-4">
        <h2 class="text-center mb-4">All Bookings</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Pickup Location</th>
                    <th>Dropoff Location</th>
                    <th>Scheduled Time</th>
                    <th>Weight (kg)</th>
                    <th>Assigned Driver</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->pickup_location }}</td>
                    <td>{{ $booking->dropoff_location }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->scheduled_time)->format('M d, Y - h:i A') }}</td>
                    <td>
                        <form action="{{ route('admin.updateBooking', $booking->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="number" name="weight" value="{{ old('weight', $booking->weight) }}" class="form-control" required min="1">
                    </td>
                    <td>
                        <select name="driver_id" class="form-select form-control" required>
                            <option value="" disabled selected>Select Driver</option>
                            @foreach ($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ $driver->id == old('driver_id', $booking->driver_id) ? 'selected' : '' }}>
                                    {{ $driver->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
