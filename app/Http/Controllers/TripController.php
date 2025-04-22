<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Trip;

class TripController extends Controller
{
    
    public function adminDashboard(Request $request)
    {
            $filter = $request->get('filter', 'today');
    
            $today = Carbon::today();
            $sevenDaysAgo = Carbon::today()->subDays(7);
            $startOfMonth = Carbon::now()->startOfMonth();
    
            $completedBookingsQuery = Booking::where('status', 'completed');
            $driverEarningsQuery = Booking::where('status', 'completed');
            // $bookingsQuery = Booking::where('status', 'pending');
            $pendingTripsQuery = Booking::where('status', 'pending');
            $completedTripsQuery = Booking::where('status', 'completed');
            $canceledTripsQuery = Booking::where('status', 'canceled');
    
            switch ($filter) {
                case 'today':
                    $completedBookingsQuery->whereDate('created_at', $today);
                    $driverEarningsQuery->whereDate('created_at', $today);
                    // $bookingsQuery->whereDate('created_at', $today);
                    $pendingTripsQuery->whereDate('created_at', $today);
                    $completedTripsQuery->whereDate('created_at', $today);
                    $canceledTripsQuery->whereDate('created_at', $today);
                    break;
    
                case 'last_7_days':
                    $completedBookingsQuery->whereBetween('created_at', [$sevenDaysAgo, $today]);
                    $driverEarningsQuery->whereBetween('created_at', [$sevenDaysAgo, $today]);
                    // $bookingsQuery->whereBetween('created_at', [$sevenDaysAgo, $today]);
                    $pendingTripsQuery->whereBetween('created_at', [$sevenDaysAgo, $today]);
                    $completedTripsQuery->whereBetween('created_at', [$sevenDaysAgo, $today]);
                    $canceledTripsQuery->whereBetween('created_at', [$sevenDaysAgo, $today]);
                    break;
    
                case 'monthly':
                    $completedBookingsQuery->whereBetween('created_at', [$startOfMonth, $today]);
                    $driverEarningsQuery->whereBetween('created_at', [$startOfMonth, $today]);
                    // $bookingsQuery->whereBetween('created_at', [$startOfMonth, $today]);
                    $pendingTripsQuery->whereBetween('created_at', [$startOfMonth, $today]);
                    $completedTripsQuery->whereBetween('created_at', [$startOfMonth, $today]);
                    $canceledTripsQuery->whereBetween('created_at', [$startOfMonth, $today]);
                    break;
            }
    
            $completedBookings = $completedBookingsQuery->get();
            $totalEarnings = $completedBookings->sum('estimated_fare');
            $pendingTripsCount = $pendingTripsQuery->count();
            $completedTrips = $completedTripsQuery->count();
            $canceledTrips = $canceledTripsQuery->count();
    
            $driverEarnings = $driverEarningsQuery
                ->select('driver_id', \DB::raw('SUM(estimated_fare) as total_earnings'))
                ->groupBy('driver_id')
                ->with('driver')
                ->get();
    
            return view('admin.dashboard', compact(
                'completedBookings',
                'totalEarnings',
                'driverEarnings',
                'filter',
                'completedTrips', 
                'canceledTrips', 
                'pendingTripsCount'
            ));
    }

    
    // public function pendingTrips()
    // {
    //     // Get the current time for comparison
    //     $now = Carbon::now();
    //     $corporate_id = auth()->user()->corporate_id;
    
    //     // Fetch all pending bookings with a scheduled_time that has passed (i.e., scheduled_time <= now)
    //     $bookings = Booking::where('corporate_id', $corporate_id)
    //                         ->where('status', 'pending')
    //                         ->where('scheduled_time', '<=', $now)  // Only bookings whose scheduled_time has passed or is now
    //                         ->get();
    
    //     // Fetch all drivers (role 'driver')
    //     $drivers = User::where('role', '3')->get();  // Assuming drivers have role '3'
    
    //     // Return the view with the pending bookings and available drivers
    //     return view('admin.pending', compact('bookings', 'drivers'));
    // }
    

    // Admin assigns weight, driver, and calculates fare

    
    public function pendingTrips(Request $request)
    {
        $now = Carbon::now();
        $corporate_id = auth()->user()->corporate_id;
        
        // Base query
        $query = Booking::where('status', 'pending')
                    ->where('scheduled_time', '<=', $now);
        
        // Apply corporate filter if exists
        if ($corporate_id) {
            $query->where(function($q) use ($corporate_id) {
                $q->where('corporate_id', $corporate_id)
                ->orWhereNull('corporate_id');
            });
        }
        
        // Check if bulk mode is requested
        $isBulkMode = $request->has('bulk');
        
        if ($isBulkMode) {
            $query->where('is_bulk', true);
        }
        
        $bookings = $query->get();
        $drivers = User::where('role', '3')->get();
        
        return view('admin.pending', [
            'bookings' => $bookings,
            'drivers' => $drivers,
            'isBulkMode' => $isBulkMode
        ]);
    }
    
    public function updateBooking(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'weight' => 'required|numeric|min:1',  
            'driver_id' => 'required|exists:users,id', 
        ]);

        $booking = Booking::findOrFail($id);  

        // Calculate transport fare based on weight ranges
        $weight = $request->input('weight');
        $estimatedFare = $this->calculateFare($weight);

        // Update booking with the weight, driver, fare, and status
        $booking->update([
            'weight' => $weight,
            'driver_id' => $request->input('driver_id'),
            'estimated_fare' => $estimatedFare,
            'status' => 'confirmed'
        ]);

        return redirect()->route('admin.pending')->with('success', 'Booking updated successfully!');
    }

    public function bulkAssign(Request $request)
    {
        $bookingIds = explode(',', $request->booking_ids);
        
        // Validate input
        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'weight' => 'required|numeric|min:1',
            'booking_ids' => 'required'
        ]);
        
        // Update all selected bookings
        Booking::whereIn('id', $bookingIds)
                ->update([
                    'driver_id' => $validated['driver_id'],
                    'weight' => $validated['weight'],
                    'status' => 'assigned' // or whatever status you use
                ]);
        
        return back()->with('success', 'Drivers assigned successfully');
    }

    // public function bulkAssignDriver(Request $request)
    // {
    //     // Validate the request
    //     $request->validate([
    //         'driver_id' => 'required|exists:users,id',
    //         'booking_ids' => 'required|array',
    //         'booking_ids.*' => 'exists:bookings,id',
    //     ]);

    //     // Get pending bulk-uploaded bookings (optional filter)
    //     $bookings = Booking::whereIn('id', $request->booking_ids)
    //         ->where('status', 'pending')
    //         ->where('is_bulk', true) // If you track bulk uploads
    //         ->get();

    //     // Assign driver and update status
    //     $bookings->each(function ($booking) use ($request) {
    //         $booking->update([
    //             'driver_id' => $request->driver_id,
    //             'status' => 'confirmed',
    //             // Add fare calculation if needed
    //             'estimated_fare' => $this->calculateFare($booking->weight ?? 0),
    //         ]);
    //     });

    //     return redirect()->back()->with('success', 'Driver assigned to ' . $bookings->count() . ' bookings!');
    // }

    // Helper function to calculate fare based on weight
    private function calculateFare($weight)
    {
        // Fare calculation based on weight ranges
        if ($weight >= 0 && $weight <= 10) {
            return 100;  // 0-10 kg
        } elseif ($weight > 10 && $weight <= 20) {
            return 200;  // 10-20 kg
        } elseif ($weight > 20 && $weight <= 30) {
            return 300;  // 20-30 kg
        } else {
            // If weight is greater than 30, apply a rate of 300 bob + additional per kg
            return 300 + (($weight - 30) * 10);
        }
    }
    public function assignedTrips()
    {  
        $bookings = Booking::where('status', 'in_progress')
                            ->get();  // Fetch confirmed bookings for all drivers

        return view('admin.assignedTrips', compact('bookings'));  // Return the driver's dashboard
    }

    public function incomereport(Request $request)
    {
        $filter = $request->get('filter', 'today');

        $today = Carbon::today();
        $sevenDaysAgo = Carbon::today()->subDays(7);
        $startOfMonth = Carbon::now()->startOfMonth();

        $completedBookingsQuery = Booking::where('status', 'completed');
        $driverEarningsQuery = Booking::where('status', 'completed');

        switch ($filter) {
            case 'today':
                $completedBookingsQuery->whereDate('created_at', $today);
                $driverEarningsQuery->whereDate('created_at', $today);
                break;

            case 'last_7_days':
                $completedBookingsQuery->whereBetween('created_at', [$sevenDaysAgo, $today]);
                $driverEarningsQuery->whereBetween('created_at', [$sevenDaysAgo, $today]);
                break;

            case 'monthly':
                $completedBookingsQuery->whereBetween('created_at', [$startOfMonth, $today]);
                $driverEarningsQuery->whereBetween('created_at', [$startOfMonth, $today]);
                break;
        }

        $completedBookings = $completedBookingsQuery->get();
        $totalEarnings = $completedBookings->sum('estimated_fare');

        $driverEarnings = $driverEarningsQuery
            ->select('driver_id', \DB::raw('SUM(estimated_fare) as total_earnings'))
            ->groupBy('driver_id')
            ->with('driver')
            ->get();

        return view('admin.reports.income', compact(
            'completedBookings',
            'totalEarnings',
            'driverEarnings',
            'filter'
        ));
    }

    public function tripreport(Request $request)
    {
    // Get the filter from the request, default to 'today'
        $filter = $request->get('filter', 'today');

        // Get the current date and time
        $today = Carbon::today();
        //dd($today);
        $sevenDaysAgo = Carbon::today()->subDays(7);
        $startOfMonth = Carbon::now()->startOfMonth();

        // Base query for Bookings
        $completedBookingsQuery = Booking::where('status', 'completed');
        $canceledBookingsQuery = Booking::where('status', 'canceled');
        $pendingQuery = Booking::where('status', 'pending');
        $inProgressQuery = Booking::where('status', 'in_progress');

        // Apply date filters based on the selected filter
        switch ($filter) {
            case 'today':
                // Filter bookings for today
                $completedBookingsQuery->whereDate('created_at', $today);
                $canceledBookingsQuery->whereDate('created_at', $today);
                $pendingQuery->whereDate('created_at', $today);
                $inProgressQuery->whereDate('created_at', $today);
                break;
            
            case 'last_7_days':
                // Filter bookings from the last 7 days
                $completedBookingsQuery->whereBetween('created_at', [$sevenDaysAgo, $today]);
                $canceledBookingsQuery->whereBetween('created_at', [$sevenDaysAgo, $today]);
                $pendingQuery->whereBetween('created_at', [$sevenDaysAgo, $today]);
                $inProgressQuery->whereBetween('created_at', [$sevenDaysAgo, $today]);
                break;
            
            case 'monthly':
                // Filter bookings for the current month
                $completedBookingsQuery->whereBetween('created_at', [$startOfMonth, $today]);
                $canceledBookingsQuery->whereBetween('created_at', [$startOfMonth, $today]);
                $pendingQuery->whereBetween('created_at', [$startOfMonth, $today]);
                $inProgressQuery->whereBetween('created_at', [$startOfMonth, $today]);
                break;
        }

    // Execute the queries
        $completedBookings = $completedBookingsQuery->get();
        $canceledBookings = $canceledBookingsQuery->get();
        $pending = $pendingQuery->get();
        $in_progress = $inProgressQuery->get();
        
        // Return the view with the filtered data
        return view('admin.reports.trips', compact('completedBookings',  'canceledBookings', 'in_progress', 'pending', 'filter'));
    }

    // corporate Admins

    public function corporateAdminDashboard(Request $request)
    {
        $user = Auth::user();

        // if ($user->role !== 'corporate admin') {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }

        $companyId = $user->corporate_id;

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');

        $tripQuery = Booking::where('corporate_id', $companyId);

        if ($startDate && $endDate) {
            $tripQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($status) {
            $tripQuery->where('status', $status);
        }

        $totalTrips = $tripQuery->count();
        $completedTrips = (clone $tripQuery)->where('status', 'completed')->count();
        $ongoingTrips = (clone $tripQuery)->where('status', 'in_progress')->count();
        $canceledTrips = (clone $tripQuery)->where('status', 'canceled')->count();
        $totalCost = (clone $tripQuery)->sum('estimated_fare');

        $trips = $tripQuery->with('driver')->latest()->paginate(10);

        return view('corporates.dashboard', compact('totalTrips', 'completedTrips', 'ongoingTrips', 'canceledTrips', 'totalCost', 'trips' ));
    }

    public function driverDashboard()
    {
        $driverId = auth()->id();

        // Count completed trips for this driver
        $completedTrips = Booking::where('driver_id', $driverId)
                                ->where('status', 'completed')
                                ->count();
        
        $assignedTrips = Booking::where('driver_id', $driverId)
                                ->where('status', 'confirmed')
                                ->count();

        // Count canceled trips for this driver
        $canceledTrips = Booking::where('driver_id', $driverId)
                                ->where('status', 'canceled')
                                ->count();
        // Get all completed trips for this driver
        $completedBookings = Booking::where('driver_id', $driverId)
                                    ->where('status', 'completed')
                                    ->get();

        // Calculate total earnings
        $totalEarnings = $completedBookings->sum('estimated_fare');


        return view('drivers.dashboard', compact('completedTrips', 'assignedTrips', 'canceledTrips', 'totalEarnings', 'completedBookings'));
    }

    public function acceptedTrips()
    {
        $driverId = auth()->id();  
        $bookings = Booking::where('driver_id', $driverId)
                            ->where('status', '!=', 'completed')
                            ->get();  

        return view('drivers.accepted', compact('bookings'));  
    }

    public function startTrip($id)
    {
        $booking = Booking::findOrFail($id);
    
        $booking->update(['status' => 'in_progress']);
    
        return back()->with('success', 'Trip started successfully.');
    }
    
    public function completeTrip($id)
    {
        $booking = Booking::findOrFail($id);
   
        $booking->update(['status' => 'completed']);
    
        return back()->with('success', 'Trip marked as completed.');
    }
    
    public function cancelTrip(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
    
        // $request->validate([
        //     'cancel_reason' => 'required|string|min:5',
        // ]);

        $booking->update([
            'status' => 'confirmed',
            'canceled_by' => 'driver',
        ]);
        
        $booking->save();
    
        return back()->with('success', 'Trip has been canceled.');
    }
    
    public function canceledTrips()
    {
        $canceledBookings = Booking::where('canceled_by', 'driver') // Fetch only driver-canceled bookings
            ->get();

        $drivers = User::where('role', '3')->get(); // Fetch all drivers

        return view('admin.canceledTrips', compact('canceledBookings', 'drivers'));
    }
    public function reassignDriver(Request $request, $id)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        $booking = Booking::findOrFail($id);

        $booking->update([
            'driver_id' => $request->input('driver_id'),
            'status' => 'in_progress',
            'canceled_by' => null,  
        ]);

        return back()->with('success', 'Driver reassigned successfully!');
    }


    public function completedTrips()
    {
        $driverId = auth()->id();

        $completedBookings = Booking::where('driver_id', $driverId)
                                    ->where('status', 'completed')
                                    ->get();

        return view('drivers.completed', compact('completedBookings'));
    }

    public function earningsSummary()
    {
        $driverId = auth()->id();

        $completedBookings = Booking::where('driver_id', $driverId)
                                    ->where('status', 'completed')
                                    ->get();

        $totalEarnings = $completedBookings->sum('estimated_fare');

        return view('drivers.earnings', compact('completedBookings', 'totalEarnings'));
    }


}























// namespace App\Http\Controllers;

// use App\Models\Trip;
// use App\Models\User;
// use App\Models\Booking;
// use Illuminate\Http\Request;

// class TripController extends Controller
// {
//     // Show all pending trips for drivers to accept
//     public function adminDashboard()
// {
//     $bookings = Booking::where('status', 'pending')->get();
//     $drivers = User::where('role', '3')->get(); // Fetch all drivers

//     // Debugging - Check if drivers are actually fetched
//     if ($drivers->isEmpty()) {
//         dd("No drivers found");
//     }

//     return view('admin.pending', compact('bookings', 'drivers'));
// }


//     public function update(Request $request, Booking $booking, $id)
//     {
//         // Validate the request
//         $request->validate([
//             'weight' => 'required|numeric',
//             'driver_id' => 'required|exists:users,id', // Ensure driver exists
//         ]);

//         $booking = Booking::findOrFail($id);

//         if (!$booking->customer_id) {
//             return back()->with('error', 'Customer ID is missing.');
//         }

//         // Update booking
//         $booking->weight = $request->input('weight');
//         $booking->driver_id = $request->input('driver_id');
//         $booking->save();

//         $weight = $request->input('weight');

//         //$farePerKm = ['bike' => 10, 'van' => 20, 'truck' => 50, 'taxi' => 30];
//         //$estimated_fare = ($farePerKm[$request->vehicle_type]) + ($weight * 5);

//         return redirect()->back()->with('success', 'Booking updated successfully!');
//     }

//     // Accept a trip
//     public function acceptTrip($bookingId)
//     {
//         $booking = Booking::findOrFail($bookingId);

//         if ($booking->driver_id) {
//             return back()->with('error', 'Trip has already been assigned to another driver.');
//         }

//         $booking->update([
//             'driver_id' => auth()->id(),
//             'status' => 'confirmed'
//         ]);

//         return back()->with('success', 'Trip accepted successfully.');
//     }

//     // Reject a trip
//     public function rejectTrip($tripId)
//     {
//         $booking = Booking::findOrFail($tripId);
//         $driverId = auth()->id();

//         if ($booking->status !== 'pending') {
//             return back()->with('error', 'Trip cannot be rejected.');
//         }

//         $booking->update([
//             'status' => 'rejected',
//             'driver_id' => $driverId
//         ]);

//         return back()->with('success', 'Trip rejected successfully.');
//     }

//     // View accepted trips for the driver
//     public function acceptedTrips()
//     {
//         $driverId = auth()->id();
//         $bookings = Booking::where('driver_id', $driverId)
//                             ->where('status', 'confirmed')
//                             ->get();

//         return view('drivers.accepted', compact('bookings'));
//     }

//     // Mark trip as completed
//     public function completeTrip($id)
//     {
//         $booking = Booking::findOrFail($id);

//         // Debug: Check if the update is actually happening
//         $updated = $booking->update(['status' => 'completed']);

//         if (!$updated) {
//             return back()->with('error', 'Status update failed.');
//         }

//         return back()->with('success', 'Trip marked as completed.');

//     }

//     public function cancelTrip(Request $request, $id)
//     {
//         $booking = Booking::findOrFail($id);

//         if ($booking->driver_id !== auth()->id()) {
//             return back()->with('error', 'Unauthorized action.');
//         }

//         $request->validate(['cancel_reason' => 'required|string']);

//         $booking->update([
//             'status' => 'canceled',
//             'cancel_reason' => $request->cancel_reason
//         ]);

//         return back()->with('success', 'Trip has been canceled.');
//     }
// public function completedTrips()
// {
//     $driverId = auth()->id();

//     // Fetch all completed trips for this driver
//     $completedBookings = Booking::where('driver_id', $driverId)
//                                 ->where('status', 'completed')
//                                 ->get();

//     return view('drivers.completed', compact('completedBookings'));
// }

// }
