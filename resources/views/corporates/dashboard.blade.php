@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Corporate Admin Dashboard</h2>

    <!-- Dashboard Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Trips</h5>
                    <h3>{{ $totalTrips }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Completed</h5>
                    <h3>{{ $completedTrips }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Ongoing</h5>
                    <h3>{{ $ongoingTrips }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Canceled</h5>
                    <h3>{{ $canceledTrips }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Cost -->
    <div class="alert alert-info">
        <strong>Total Cost:</strong> Ksh{{ number_format($totalCost, 2) }}
    </div>

    <!-- Filters -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="">All</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- Trip List -->
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Company Trips</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Status</th>
                        <th>Driver</th>
                        <th>Fare</th>
                        <th>Pickup</th>
                        <th>Dropoff</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trips as $trip)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge bg-{{ getStatusColor($trip->status) }}">{{ ucfirst($trip->status) }}</span></td>
                            <td>{{ $trip->driver->name ?? 'Unassigned' }}</td>
                            <td>Ksh.{{ number_format($trip->estimated_fare, 2) }}</td>
                            <td>{{ $trip->pickup_location }}</td>
                            <td>{{ $trip->dropoff_location }}</td>
                            <td>{{ $trip->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No trips found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $trips->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
