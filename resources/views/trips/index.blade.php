  @extends('layouts.app')  

  @section('content')
<div class="container-fluid">
    <div class="card shadow p-4">
        <h2 class="text-center mb-4">Trips Management</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Pickup</th>
                        <th>Dropoff</th>
                        <th>Status</th>
                        <th>Driver</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trips as $trip)
                    <tr>
                        <td>{{ $trip->id }}</td>
                        <td>{{ $trip->customer->name ?? 'Unknown' }}</td>
                        <td>{{ $trip->pickup_location }}</td>
                        <td>{{ $trip->dropoff_location }}</td>
                        <td>
                            <span class="badge 
                                @if($trip->status == 'pending') bg-warning 
                                @elseif($trip->status == 'in_progress') bg-primary 
                                @elseif($trip->status == 'delivered') bg-success 
                                @elseif($trip->status == 'canceled') bg-danger 
                                @endif">
                                {{ ucfirst($trip->status) }}
                            </span>
                        </td>
                        <td>
                            @if($trip->driver && $trip->driver->name)
                                {{ $trip->driver->name }}
                            @else
                                Not Assigned
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                @if(!$trip->driver)
                                    <form action="{{ route('trips.assignDriver', $trip->id) }}" method="POST" class="d-flex gap-2">
                                        @csrf
                                        <select name="driver_id" class="form-select form-select-sm w-auto">
                                            @foreach(App\Models\User::where('role', 'driver')->get() as $driver)
                                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-success">Assign</button>
                                    </form>
                                @endif
                        
                                @if($trip->status != 'canceled')
                                    <form action="{{ route('trips.cancel', $trip->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="text" name="cancel_reason" class="form-control form-control-sm w-auto" placeholder="Reason" required>
                                        <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection