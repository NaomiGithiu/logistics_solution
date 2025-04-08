@extends('layouts.app')

@section('content')

<div class="container-fluid d-flex justify-content-center">
    <div class="card shadow p-4" style="max-width: 500px; width: 100%;">
        <h2 class="text-center mb-4">Book a Ride</h2>

        <div style="color: red">
            @if (session('error'))
            {{ session('error') }}
            @endif
        </div>

        <form action="{{ route('bookings.create') }}" method="POST">
            @csrf
            <input type="hidden" name="customer_id" value="{{ auth()->id() }}">

            <!-- Pickup Location -->
            <div class="mb-3">
                <label for="pickup_location" class="form-label">Pickup Location</label>
                <input type="text" id="pickup_location" name="pickup_location" class="form-control" required>
                @error('pickup_location')
                    <div class="alert alert-danger mt-2 p-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Dropoff Location -->
            <div class="mb-3">
                <label for="dropoff_location" class="form-label">Dropoff Location</label>
                <input type="text" id="dropoff_location" name="dropoff_location" class="form-control" required>
                @error('dropoff_location')
                    <div class="alert alert-danger mt-2 p-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Vehicle Type -->
            <div class="mb-3">
                <label for="vehicle_type" class="form-label">Vehicle Type</label>
                <select id="vehicle_type" name="vehicle_type" class="form-select form-control">
                    <option value="bike">Bike</option>
                    <option value="van">Van</option>
                    <option value="truck">Truck</option>
                </select>
                @error('vehicle_type')
                    <div class="alert alert-danger mt-2 p-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Ride Type (Express or Standard) -->
            <div class="mb-3 text-center">
                <!-- Hidden input to store ride_type value -->
                <input type="hidden" id="ride_type" name="ride_type" value="express">
                <button type="button" id="express_btn" class="btn btn-success mx-2" onclick="selectRideType('express')">Express (Now)</button>
                <button type="button" id="standard_btn" class="btn btn-primary mx-2" onclick="selectRideType('standard')">Standard (Schedule)</button>
            </div>

            <!-- Scheduled Ride (Hidden by default) -->
            <div class="mb-3" id="scheduled_time_div" style="display: none;">
                <label for="scheduled_time" class="form-label">Schedule Ride</label>
                <input type="datetime-local" id="scheduled_time" name="scheduled_time" class="form-control">
            </div>

            <!-- Confirm Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Confirm Booking</button>
            </div>
        </form>
    </div>
</div>

<script>
   // Function to handle ride type selection
function selectRideType(type) {
    var scheduledTimeDiv = document.getElementById('scheduled_time_div');
    var expressBtn = document.getElementById('express_btn');
    var standardBtn = document.getElementById('standard_btn');
    var scheduledTimeInput = document.getElementById('scheduled_time');
    var rideTypeInput = document.getElementById('ride_type'); // Hidden input for ride_type

    // Set active button styling
    expressBtn.classList.remove('btn-success');
    expressBtn.classList.add('btn-secondary');
    standardBtn.classList.remove('btn-primary');
    standardBtn.classList.add('btn-secondary');

    if (type === 'express') {
        // Set the scheduled time to the current date/time for express booking
        var currentDate = new Date().toISOString().slice(0, 16); // Format to 'YYYY-MM-DDTHH:mm'
        scheduledTimeInput.value = currentDate;  // Update the hidden input for scheduled_time
        scheduledTimeDiv.style.display = 'none';  // Hide the schedule input for express
        expressBtn.classList.remove('btn-secondary');
        expressBtn.classList.add('btn-success');  // Highlight express button

        // Set the hidden ride_type value to express
        rideTypeInput.value = 'express';
    } else {
        scheduledTimeDiv.style.display = 'block';  // Show the schedule input for standard
        standardBtn.classList.remove('btn-secondary');
        standardBtn.classList.add('btn-primary');  // Highlight standard button
        
        // Set the hidden ride_type value to standard
        rideTypeInput.value = 'standard';
    }
}

// Initialize the form when the page loads (default to express booking)
window.onload = function() {
    selectRideType('express');
};

</script>

@endsection
