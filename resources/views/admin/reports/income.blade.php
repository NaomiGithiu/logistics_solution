@extends('layouts.app')

@section('content')

{{-- date filter --}}
    <form action="{{ route('incomereport') }}" method="GET">
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


    <div class="container_fluid mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Driver Earnings Report</h5>
                <h5 class="mb-0">Total Earnings: {{$totalEarnings}}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>Driver Name</th>
                                <th>Total Earnings (KES)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($driverEarnings as $earning)
                                <tr>
                                    <td>{{ $earning->driver->name ?? 'Unknown Driver' }}</td>
                                    <td class="font-weight-bold text-success">
                                        KES {{ number_format($earning->total_earnings, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection