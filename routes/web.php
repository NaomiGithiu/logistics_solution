<?php

use App\Http\Controllers\CorporateController;
use App\Http\Controllers\DriverController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

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
    //Route::post('/profile/update-password', [ProfileController::class, 'updatePassword']);
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


Route::middleware(['auth'])->group(function () {
    Route::resource('roles', RoleController::class);
});


Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('dashboard', [TripController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('admin/pending', [TripController::class, 'pendingTrips'])->name('admin.pending');
    Route::put('update/{id}', [TripController::class, 'updateBooking'])->name('admin.updateBooking');
    Route::get('admin/assignedTrips', [TripController::class, 'assignedTrips'])->name('assignedTrips');
    Route::get('/admin/reports/trip', [TripController::class, 'tripreport'])->name('tripreport');
    Route::get('/admin/report/income', [TripController::class, 'incomereport'])->name('incomereport');
    Route::get('/admin/canceled-trips', [TripController::class, 'canceledTrips'])->name('admin.canceledTrips');
    Route::post('/admin/reassign-driver/{id}', [TripController::class, 'reassignDriver'])->name('admin.reassignDriver');

});

Route::prefix('customer')->middleware('auth')->group(function () {
    Route::get('booking/{id}', [TripController::class, 'viewBooking'])->name('customer.viewBooking');
});

Route::prefix('driver')->middleware('auth')->group(function () {
    Route::get('drivers/dashboard', [TripController::class, 'driverDashboard'])->name('driver.dashboard');
    Route::get('/trips/accepted', [TripController::class, 'acceptedTrips'])->name('trips.accepted');
    Route::put('/trips/{id}/complete', [TripController::class, 'completeTrip'])->name('trips.complete');
    Route::get('/trips/completedTrip', [TripController::class, 'completedTrips'])->name('trips.completed');
    Route::get('/earnings', [TripController::class, 'earningsSummary'])->name('earnings');
    Route::put('/trips/{id}/cancel', [TripController::class, 'cancelTrip'])->name('trips.cancel');
    Route::post('/trips/{id}/start', [TripController::class, 'startTrip'])->name('trips.start');

});

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Corporates

Route::resource('/corporates', CorporateController::class);
Route::get('corporates/{corporateId}/add_admin', [CorporateController::class, 'addAdminForm'])->name('corporates.addAdminForm');
Route::post('corporates/{corporateId}/add_admin', [CorporateController::class, 'addAdmin'])->name('corporates.addAdmin');

