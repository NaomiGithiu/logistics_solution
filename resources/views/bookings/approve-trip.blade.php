@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Review Trip Request #{{ $trip->id }}</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Trip Details</h5>
                    <p><strong>Customer:</strong> {{ $trip->customer->name }}</p>
                    <p><strong>Pickup:</strong> {{ $trip->pickup_location }}</p>
                    <p><strong>Dropoff:</strong> {{ $trip->dropoff_location }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Booking Information</h5>
                    <p><strong>Vehicle Type:</strong> {{ ucfirst($trip->vehicle_type) }}</p>
                    <p><strong>Scheduled Time:</strong> {{ $trip->scheduled_time->format('M d, Y H:i') }}</p>
                    <p><strong>Requested At:</strong> {{ $trip->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>

            <form action="{{ route('bookings.approve', $trip->id) }}" method="POST" class="mb-3">
                @csrf
                <div class="form-group">
                    <label for="comments">Approval Comments (Optional)</label>
                    <textarea name="comments" id="comments" class="form-control" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Approve Trip
                </button>
            </form>

            <form action="{{ route('bookings.reject', $trip->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="rejection_reason">Rejection Reason (Required)</label>
                    <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="2" required></textarea>
                </div>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times"></i> Reject Trip
                </button>
            </form>
        </div>
    </div>
</div>
@endsection