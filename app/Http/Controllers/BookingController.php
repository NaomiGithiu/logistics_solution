<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmation;

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
    $request->validate([
        'customer_id' => 'required|exists:users,id',
        'pickup_location' => 'required|string',
        'dropoff_location' => 'required|string',
        'vehicle_type' => 'required|in:bike,van,truck,taxi',
        'scheduled_time' => 'nullable|date|after:now',
    ]);

    // Calculate estimated fare (dummy logic)
    // $distance = rand(5, 50);
    // $weight = rand(1, 10);
    // $farePerKm = ['bike' => 10, 'van' => 20, 'truck' => 50, 'taxi' => 30];
    // $estimated_fare = ($distance * $farePerKm[$request->vehicle_type]) + ($weight * 5);

    $customer_id = auth()->id();
    if (!$customer_id) {
        return back()->withErrors(['error' => 'User must be logged in to book.']);
    }

    // Assign an available driver
    $driver = User::where('role', 'driver')->whereDoesntHave('trips', function($query) {
        $query->whereIn('status', ['pending', 'in_progress']);
    })->first();

    // Create booking
    $booking = Booking::create([
        'customer_id' => $customer_id,
        'pickup_location' => $request->pickup_location,
        'dropoff_location' => $request->dropoff_location,
       // 'estimated_fare' => $estimated_fare,
        'vehicle_type' => $request->vehicle_type,
        'scheduled_time' => $request->scheduled_time ? date('Y-m-d H:i:s', strtotime($request->scheduled_time)) : null,
        'status' => 'pending',
        'rider_id' => $driver ? $driver->id : null, 
    ]);

    // Send confirmation email
    Mail::to($booking->customer->email)->send(new BookingConfirmation($booking));

    return redirect()->route('bookings.details', ['id' => $booking->id])
        ->with('success', 'Booking created successfully!'. ($driver ? ' Driver assigned: ' . $driver->name : ' No driver available.'));
}

   public function show($id)
    {
        $booking = Booking::with(['trip.driver'])->findOrFail($id);
        return view('customer.details', compact('booking'));
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

}
