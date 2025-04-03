<?php

use App\Http\Controllers\DriverController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TripController;

Route::get("/", [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'store'])->name('store');


Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/logout', [LogoutController::class, 'store'])->name('logout');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['web'])->group(function () {
    Route::get('/verifyaccount', [HomeController::class, 'verifyaccount'])->name('verifyaccount');
    Route::post('/verifyotp', [HomeController::class, 'useractivation'])->name('verifyotp');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
});

// booking
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [BookingController::class, 'customerDashboard'])->name('customer.dashboard');
    Route::get('/bookings', [BookingController::class, 'index'])->name('booking');
    Route::post('/bookings', [BookingController::class, 'create'])->name('bookings.create');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.details');
    Route::patch('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
    Route::get('/report', [BookingController::class, 'report'])->name('customer-report');
});

Route::resource('/users', DriverController::class);

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('dashboard', [TripController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('admin/pending', [TripController::class, 'pendingTrips'])->name('admin.pending');
    Route::put('update/{id}', [TripController::class, 'updateBooking'])->name('admin.updateBooking');
    Route::get('admin/assignedTrips', [TripController::class, 'assignedTrips'])->name('assignedTrips');
    Route::get('/admin/report', [TripController::class, 'report'])->name('report');
});

Route::prefix('customer')->middleware('auth')->group(function () {
    Route::get('booking/{id}', [TripController::class, 'viewBooking'])->name('customer.viewBooking');
});

Route::prefix('driver')->middleware('auth')->group(function () {
    Route::get('drivers/dashboard', [TripController::class, 'driverDashboard'])->name('driver.dashboard');
    Route::get('/trips/accepted', [TripController::class, 'acceptedTrips'])->name('trips.accepted');
    Route::put('/trips/{id}/completeTrip', [TripController::class, 'completeTrip'])->name('trips.complete');
    Route::get('/trips/completedTrip', [TripController::class, 'completedTrips'])->name('trips.completed');
    Route::get('/earnings', [TripController::class, 'earningsSummary'])->name('earnings');
});



// Route::get('/admin/pending', [TripController::class, 'adminDashboard'])->name('admin.pending');
// Route::put('/trips/{trip}', [TripController::class, 'update'])->name('bookings.update');

// Route::post('/trips/{trip}/accept', [TripController::class, 'acceptTrip'])->name('bookings.accept');
// Route::post('/trips/{trip}/reject', [TripController::class, 'rejectTrip'])->name('bookings.reject');

// Route::get('/trips/accepted', [TripController::class, 'acceptedTrips'])->name('trips.accepted');
// Route::put('/trips/{id}/complete', [TripController::class, 'completeTrip'])->name('trips.complete');
// Route::put('/trips/{id}/cancel', [TripController::class, 'cancelTrip'])->name('trips.cancel');
// Route::get('/trips/completed', [TripController::class, 'completedTrips'])->name('trips.completed');

    

