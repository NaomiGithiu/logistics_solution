<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmation;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BookingTemplateExport;


class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensures user is logged in
    }

    public function customerDashboard(){
        return view('customer.dashboard');
    }

    public function index(){
        return view('customer.create');
    }

    public function create(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'pickup_location' => 'required|string',
            'dropoff_location' => 'required|string',
            'vehicle_type' => 'required|in:bike,van,truck,taxi',
            'scheduled_time' => $request->ride_type === 'express' ? 'nullable' : 'nullable|date|after:now', // Allow nullable for express
            'ride_type' => 'required|in:express,standard',
        ]);

        // Check if the ride is express or standard
        if ($request->ride_type === 'express') {
            $scheduled_time = now();
        } else {
            $scheduled_time = $request->scheduled_time ? Carbon::parse($request->scheduled_time) : null;
        }

        $customer_id = auth()->user()->id;
        $corporate_id = auth()->corporate_id();

        $driver = User::where('role', 'driver')->whereDoesntHave('trips', function($query) {
            $query->whereIn('status', ['pending', 'in_progress']);
        })->first();

        $booking = Booking::create([
            'customer_id' => $customer_id,
            'corporate_id' => $corporate_id,
            'pickup_location' => $request->pickup_location,
            'dropoff_location' => $request->dropoff_location,
            'vehicle_type' => $request->vehicle_type,
            'scheduled_time' => $scheduled_time,
            'status' => 'pending',
            'ride_type' => $request->ride_type,
            'rider_id' => $driver ? $driver->id : null,
            'is_bulk' => false, // default to single booking
            'trip_id' => null,  // not yet assigned to a trip
        ]);

        Mail::to($booking->customer->email)->send(new BookingConfirmation($booking));

        return redirect()->route('bookings.details', ['id' => $booking->id])
            ->with('success', 'Booking created successfully!');
    }

    public function show($id)
    {
        $customerId = auth()->id();
        $details = Booking::where('customer_id', $customerId)
                        ->where('status', '!=', 'completed')
                        ->get();
        return view('customer.details', compact('details'));
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending rides can be canceled.');
        }

        $booking->update(['status' => 'canceled']);

        return redirect()->route('bookings.details', $id)
                            ->with('success', 'Booking has been canceled successfully.');
    }

    public function myBookings()
    {
        $customerId = auth()->id();

        $bookings = Booking::where('customer_id', $customerId)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('customer.my_booking', compact('bookings'));
    }

    public function report()
    {
        $customerId = auth()->id();

        $totalTrips = Booking::where('customer_id', $customerId)->count();

        $completedbookings = Booking::where('customer_id', $customerId)
                            ->where('status', 'completed')
                            ->orderBy('created_at', 'desc')
                            ->get();

        $totalFare = $completedbookings->sum('estimated_fare');

        $canceledbookings = Booking::where('customer_id', $customerId)
                            ->where('status', 'canceled')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('customer.report', compact('completedbookings', 'canceledbookings', 'totalTrips', 'totalFare'));
    }


    // Bulk Booking
    public function showBulkForm()
    {
        return view('bookings.bulk');
    }
 

    public function downloadTemplate()
    {
        return Excel::download(new BookingTemplateExport, 'booking_template.xlsx');
    }

    public function handleBulkUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            $collection = Excel::toCollection(null, $request->file('file'))->first();

            $corporate_id = auth()->user()->corporate_id; 
            foreach ($collection as $index => $row) {
                // Skip empty rows
                if (empty($row[0]) && empty($row[1]) && empty($row[2]) && empty($row[3])) {
                    continue;
                }

                $pickup_location = $row[0];
                $dropoff_location = $row[1];
                $vehicle_type = strtolower(trim($row[2]));
                $ride_type = strtolower(trim($row[3]));
                $scheduled_time_input = $row[4] ?? null;

                // Validate minimal ride_type and vehicle_type options
                if (!in_array($ride_type, ['express', 'standard']) || !in_array($vehicle_type, ['bike', 'van', 'truck', 'taxi'])) {
                    continue; // skip invalid rows
                }

                $scheduled_time = $ride_type === 'express'
                    ? now()
                    : ($scheduled_time_input ? Carbon::parse($scheduled_time_input) : null);

                // Optionally assign a driver
                $driver = User::where('role', 'driver')->whereDoesntHave('trips', function ($query) {
                    $query->whereIn('status', ['pending', 'in_progress']);
                })->first();

                $customer_id = auth()->user()->id;

                $booking = Booking::create([
                    'customer_id' => $customer_id,
                    'corporate_id' => $corporate_id,
                    'pickup_location' => $pickup_location,
                    'dropoff_location' => $dropoff_location,
                    'vehicle_type' => $vehicle_type,
                    'scheduled_time' => $scheduled_time,
                    'status' => 'pending',
                    'ride_type' => $ride_type,
                    'rider_id' => $driver ? $driver->id : null,
                    'is_bulk' => true,
                    'trip_id' => null,
                ]);

                // Send confirmation email if customer is available (optional)
                if ($booking->customer?->email) {
                    Mail::to($booking->customer->email)->send(new BookingConfirmation($booking));
                }
            }

            return redirect()->route('bookings.bulk')->with('success', 'Bookings uploaded successfully!');
        } catch (\Exception $e) {
            return redirect()->route('bookings.bulk')->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    public function pendingApprovals()
    {

        $pendingTrips = Booking::where('status', 'pending')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('bookings.pending-approvals', compact('pendingTrips'));
    }

    public function showForApproval($id)
    {
        $trip = Booking::findOrFail($id);
        return view('bookings.approve-trip', compact('trip'));
    }

    public function approveTrip(Request $request, $id)
    {

        $trip = Booking::findOrFail($id);
        
        $request->validate([
            'comments' => 'nullable|string|max:255',
        ]);

        $trip->update([
            'status' => 'confirmed',
            'approved_by' => auth()->id(),
            'approval_comments' => $request->comments,
            'approved_at' => now(),
        ]);

        // Send notification to maker/customer
        Mail::to($trip->customer->email)->send(new \App\Mail\TripApproved($trip));

        return redirect()->route('bookings.pending-approvals')
                        ->with('success', 'Trip approved successfully!');
    }

    public function rejectTrip(Request $request, $id)
    {

        $trip = Booking::findOrFail($id);
        
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $trip->update([
            'status' => 'rejected',
            'rejected_by' => auth()->id(),
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
        ]);

        // Send notification to maker/customer
        Mail::to($trip->customer->email)->send(new \App\Mail\TripRejected($trip));

        return redirect()->route('bookings.pending-approvals')
                        ->with('success', 'Trip rejected successfully!');
    }


    
}
