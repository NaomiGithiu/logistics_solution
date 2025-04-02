{{-- @include('layouts.app') --}}


<link href="{{asset('css/style.css')}}" rel="stylesheet">

    <div class="booking-wrapper">
        <div class="booking-container">
            <h2>Book a Ride</h2>

            <form action="{{ route('bookings.create') }}" method="POST">
                @csrf
                <input type="hidden" name="customer_id" value="{{ auth()->id() }}">

                <label for="pickup_location">Pickup Location</label>
                <input type="text" id="pickup_location" name="pickup_location">
                @error('pickup_location')
                    <div style="color: red">
                        {{$message}}
                    </div>
                @enderror

                <label for="dropoff_location">Dropoff Location</label>
                <input type="text" id="dropoff_location" name="dropoff_location">
                @error('dropoff_location')
                    <div style="color: red">
                        {{$message}}
                    </div>
                @enderror

                <label for="vehicle_type">Vehicle Type</label>
                <select id="vehicle_type" name="vehicle_type">
                    <option value="bike">Bike</option>
                    <option value="van">Van</option>
                    <option value="truck">Truck</option>
                </select>
                @error('vehicle_type')
                    <div style="color: red">
                        {{$message}}
                    </div>
                @enderror

                <label for="scheduled_time">Schedule Ride</label>
                <input type="datetime-local" id="scheduled_time" name="scheduled_time">

                <button type="submit">Confirm Booking</button>
            </form>
        </div>
    </div>

