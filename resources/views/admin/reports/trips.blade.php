@extends('layouts.app')

@section('content')

{{-- date filter --}}
<form action="{{ route('tripreport') }}" method="GET">
    <div class="form-group">
        <label for="filter">Filter By</label>
        <select name="filter" id="filter" class="form-control w-25">
            <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Today</option>
            <option value="last_7_days" {{ request('filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
            <option value="monthly" {{ request('filter') == 'monthly' ? 'selected' : '' }}>Monthly</option>
        </select>        
    </div>
    <button type="submit" class="btn btn-primary">Apply Filter</button>
</form>

<div class="container-fluid mt-4">
    <div class="accordion" id="reportAccordion">

        {{-- Pending Trips --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingPending">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePending" aria-expanded="true" aria-controls="collapsePending">
                    Pending Trips
                </button>
            </h2>
            <div id="collapsePending" class="accordion-collapse collapse show" aria-labelledby="headingPending" data-bs-parent="#reportAccordion">
                <div class="accordion-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle shadow-sm">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>Trip ID</th>
                                    <th>Pickup Location</th>
                                    <th>Dropoff Location</th>
                                    <th>Weight (kg)</th>
                                    <th>Fare (Ksh)</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <tbody class="table-light">
                                @foreach($pending as $pendingbooking)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $pendingbooking->id }}</td>
                                        <td>{{ $pendingbooking->pickup_location }}</td>
                                        <td>{{ $pendingbooking->dropoff_location }}</td>
                                        <td class="text-center">{{ $pendingbooking->weight }}</td>
                                        <td class="text-success fw-bold"> {{ number_format($pendingbooking->estimated_fare, 2) }}</td>
                                        <td class="text-center">{{ $pendingbooking->updated_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>                            
                    </div>
                </div>
            </div>
        </div>

        {{-- In Progress Trips --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingInProgress">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInProgress" aria-expanded="false" aria-controls="collapseInProgress">
                    In Progress Trips
                </button>
            </h2>
            <div id="collapseInProgress" class="accordion-collapse collapse" aria-labelledby="headingInProgress" data-bs-parent="#reportAccordion">
                <div class="accordion-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle shadow-sm">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>Trip ID</th>
                                    <th>Pickup Location</th>
                                    <th>Dropoff Location</th>
                                    <th>Weight (kg)</th>
                                    <th>Fare (Ksh)</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <tbody class="table-light">
                                @foreach($in_progress as $in_progressbooking)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $in_progressbooking->id }}</td>
                                        <td>{{ $in_progressbooking->pickup_location }}</td>
                                        <td>{{ $in_progressbooking->dropoff_location }}</td>
                                        <td class="text-center">{{ $in_progressbooking->weight }}</td>
                                        <td class="text-success fw-bold"> {{ number_format($in_progressbooking->estimated_fare, 2) }}</td>
                                        <td class="text-center">{{ $in_progressbooking->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>                            
                    </div>
                </div>
            </div>
        </div>

        {{-- Completed Trips --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCompleted">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCompleted" aria-expanded="false" aria-controls="collapseCompleted">
                    Completed Trips
                </button>
            </h2>
            <div id="collapseCompleted" class="accordion-collapse collapse" aria-labelledby="headingCompleted" data-bs-parent="#reportAccordion">
                <div class="accordion-body">
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
                </div>
            </div>
        </div>

        {{-- Canceled Trips --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCanceled">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCanceled" aria-expanded="false" aria-controls="collapseCanceled">
                    Canceled Trips
                </button>
            </h2>
            <div id="collapseCanceled" class="accordion-collapse collapse" aria-labelledby="headingCanceled" data-bs-parent="#reportAccordion">
                <div class="accordion-body">
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
            </div>
        </div>

    </div>
</div>

@endsection
