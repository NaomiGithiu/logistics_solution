<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmation;
use Carbon\Carbon;

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
            // If it's express, set the scheduled time to the current time
            $scheduled_time = now(); // Using Carbon for current date/time
        } else {
            // Otherwise, use the scheduled time provided in the form
            $scheduled_time = $request->scheduled_time ? Carbon::parse($request->scheduled_time) : null;
        }

        // Get the customer ID from the authenticated user
        $customer_id = auth()->id();
    
        // Assign an available driver
        $driver = User::where('role', 'driver')->whereDoesntHave('trips', function($query) {
            $query->whereIn('status', ['pending', 'in_progress']);
        })->first();
    
        // Create the booking
        $booking = Booking::create([
            'customer_id' => $customer_id,
            'pickup_location' => $request->pickup_location,
            'dropoff_location' => $request->dropoff_location,
            'vehicle_type' => $request->vehicle_type,
            'scheduled_time' => $scheduled_time, // Set based on express or standard
            'status' => 'pending',
            'ride_type' => $request->ride_type, // Save the ride type (express/standard)
            'rider_id' => $driver ? $driver->id : null,
        ]);
    
        // Send confirmation email
        Mail::to($booking->customer->email)->send(new BookingConfirmation($booking));
    
        return redirect()->route('bookings.details', ['id' => $booking->id])
            ->with('success', 'Booking created successfully!');
    }
    
        
   public function show($id)
    {
        //$booking = Booking::with(['trip.driver'])->findOrFail($id);
        $customerId = auth()->id();
        $details = Booking::where('customer_id', $customerId)
                        ->where('status', '!=', 'completed')
                        ->get();
        return view('customer.details', compact('details'));
        //return view('customer.details', compact('booking'));
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
        $customerId = auth()->id(); // Get the logged-in customer ID

        $bookings = Booking::where('customer_id', $customerId)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('customer.my_booking', compact('bookings'));
    }


    public function report()
    {
        $customerId = auth()->id(); // Get the logged-in customer ID

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


}
