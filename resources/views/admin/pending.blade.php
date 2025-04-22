@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Mode Selection Card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Select Management Mode</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user-tie fa-4x mb-3 text-primary"></i>
                            <h4>Single Booking Mode</h4>
                            <p class="text-muted">Assign drivers individually with custom weights</p>
                            <a href="{{ route('admin.pending') }}" class="btn btn-primary">
                                <i class="fas fa-user"></i> Switch to Single Mode
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-4x mb-3 text-success"></i>
                            <h4>Bulk Booking Mode</h4>
                            <p class="text-muted">Assign the same driver to multiple bookings</p>
                            <a href="{{ route('admin.pending', ['bulk' => true]) }}" class="btn btn-success">
                                <i class="fas fa-users"></i> Switch to Bulk Mode
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($isBulkMode)
        <!-- Bulk Assignment Form -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Bulk Driver Assignment</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('bookings.bulk-assign') }}" method="POST" id="bulkForm">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Select Driver</label>
                            <select name="driver_id" class="form-select" required>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Assign to Selected
                                </button>
                            </div>
                           
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50px">
                                        {{-- <input type="checkbox" id="selectAll"> --}}
                                    </th>
                                    <th>Booking ID</th>
                                    <th>Corporate ID</th>
                                    <th>Pickup Location</th>
                                    <th>Scheduled Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="booking_ids[]" value="{{ $booking->id }}" class="booking-checkbox">
                                    </td>
                                    <td>{{ $booking->id }}</td>
                                    <td>{{ $booking->corporate_id }}</td>
                                    <td>{{ $booking->pickup_location }}</td>
                                    <td>{{ $booking->scheduled_time->format('M d, Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    @else
        <!-- Single Assignment Table -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Single Booking Management</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Booking ID</th>
                                <th>Corporate ID</th>
                                <th>Pickup Location</th>
                                <th>Scheduled Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>{{ $booking->corporate_id }}</td>
                                <td>{{ $booking->pickup_location }}</td>
                                <td>{{ $booking->scheduled_time->format('M d, Y H:i') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                            data-bs-target="#assignModal{{ $booking->id }}">
                                        <i class="fas fa-user-edit"></i> Assign Driver
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Single Assignment Modals -->
        @foreach($bookings as $booking)
        <div class="modal fade" id="assignModal{{ $booking->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Assign Driver to Booking #{{ $booking->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Weight (kg)</label>
                                <input type="number" name="weight" class="form-control" required min="1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Select Driver</label>
                                <select name="driver_id" class="form-select" required>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Confirm Assignment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>

<!-- Bulk assingment -->
<div class="modal fade" id="assignModal{{ $booking->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="assignmentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Assign Driver to <span id="modalTitle">Selected Bookings</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="booking_ids" id="bookingIds">
                    <div class="mb-3">
                        <label class="form-label">Weight (kg)</label>
                        <input type="number" name="weight" class="form-control" required min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Driver</label>
                        <select name="driver_id" class="form-select" required>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm Assignment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Bulk selection toggle
    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.booking-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Handle assignment modal
    document.getElementById('assignBtn')?.addEventListener('click', function() {
        const selectedBookings = Array.from(document.querySelectorAll('.booking-checkbox:checked'))
                                .map(checkbox => checkbox.value);
        
        if (selectedBookings.length === 0) {
            alert('Please select at least one booking');
            return;
        }

        const modalTitle = document.getElementById('modalTitle');
        if (selectedBookings.length === 1) {
            modalTitle.textContent = `Booking #${selectedBookings[0]}`;
            document.getElementById('assignmentForm').action = `/bookings/${selectedBookings[0]}`;
        } else {
            modalTitle.textContent = `${selectedBookings.length} Selected Bookings`;
            document.getElementById('assignmentForm').action = '/bookings/bulk-assign';
        }
        
        document.getElementById('bookingIds').value = selectedBookings.join(',');
        new bootstrap.Modal(document.getElementById('assignModal')).show();
    });
</script>
@endpush
@endsection