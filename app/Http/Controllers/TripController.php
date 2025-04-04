<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class TripController extends Controller
{
    
    public function adminDashboard()
    {
        $pendingTripsCount = Booking::where('status', 'pending')->count();

        // Count all completed trips
        $completedTrips = Booking::where('status', 'completed')->count();

        // Count all canceled trips
        $canceledTrips = Booking::where('status', 'canceled')->count();

         // Fetch total earnings per driver
        $driverEarnings = Booking::where('status', 'completed')
        ->select('driver_id', \DB::raw('SUM(estimated_fare) as total_earnings'))
        ->groupBy('driver_id')
        ->with('driver') // Assuming a relationship exists in Booking model
        ->get();

        $totalEarnings = $driverEarnings->sum('total_earnings');

        return view('admin.dashboard', compact('completedTrips', 'canceledTrips', 'pendingTripsCount', 'driverEarnings', 'totalEarnings'));
    }

    
    // Show all pending bookings for admin to review and assign
    public function pendingTrips()
    {
        $bookings = Booking::where('status', 'pending')->get();  // Fetch all pending bookings
        $drivers = User::where('role', '3')->get();  // Fetch all drivers (role 'driver')

        // Return the view with the pending bookings and available drivers
        return view('admin.pending', compact('bookings', 'drivers'));
    }

    // Admin assigns weight, driver, and calculates fare
    public function updateBooking(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'weight' => 'required|numeric|min:1',  // Ensure weight is provided
            'driver_id' => 'required|exists:users,id',  // Ensure driver ID exists
        ]);

        $booking = Booking::findOrFail($id);  // Find the booking by ID

        // Calculate transport fare based on weight ranges
        $weight = $request->input('weight');
        $estimatedFare = $this->calculateFare($weight);

        // Update booking with the weight, driver, fare, and status
        $booking->update([
            'weight' => $weight,
            'driver_id' => $request->input('driver_id'),
            'estimated_fare' => $estimatedFare,
        ]);

        return redirect()->route('admin.pending')->with('success', 'Booking updated successfully!');
    }

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
        $bookings = Booking::where('status', 'confirmed')
                            ->get();  // Fetch confirmed bookings for all drivers

        return view('admin.assignedTrips', compact('bookings'));  // Return the driver's dashboard
    }

    public function report()
    {
        $completedBookings = Booking::where('status', 'completed')
                                    ->get();
           
        $canceledBookings = Booking::where('status', 'canceled')
        ->get();

        $totalEarnings = $completedBookings->sum('estimated_fare');

        $driverEarnings = Booking::where('status', 'completed')
                                ->select('driver_id', \DB::raw('SUM(estimated_fare) as total_earnings'))
                                ->groupBy('driver_id')
                                ->with('driver') 
                                ->get();

        return view('admin.report', compact('completedBookings', 'totalEarnings', 'canceledBookings', 'driverEarnings'));
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
    
        $booking->update(['status' => 'confirmed']);
    
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
            'status' => 'canceled',
            'canceled_by' => 'driver',
            'cancel_reason' => $request->cancel_reason,
        ]);
        
        $booking->save();

        dd($booking->canceled_by);
    
        return back()->with('success', 'Trip has been canceled.');
    }
    
    public function canceledTrips()
    {
        $canceledBookings = Booking::where('status', 'canceled')
            ->where('canceled_by', 'driver') // Fetch only driver-canceled bookings
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
            'status' => 'confirmed',
            'canceled_by' => null, 
            'cancel_reason' => null, 
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
