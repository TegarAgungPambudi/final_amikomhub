<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\OrganizerController;
use App\Http\Controllers\Auth\SocialiteController;

// Rute User Area
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/organizer/{slug}', [\App\Http\Controllers\OrganizerProfileController::class, 'show'])->name('organizer.profile');
Route::get('/organizer/register', [\App\Http\Controllers\OrganizerRegistrationController::class, 'showForm'])->name('organizer.register');
Route::post('/organizer/register', [\App\Http\Controllers\OrganizerRegistrationController::class, 'store'])->name('organizer.register.store')->middleware('auth');
Route::get('/checkout/{event}', [\App\Http\Controllers\PaymentController::class,'checkout'])->name('checkout');
Route::post('/checkout/{event}/process', [\App\Http\Controllers\PaymentController::class,'processCheckout'])->name('checkout.process');
Route::get('/payment-status/{transaction}', [\App\Http\Controllers\PaymentController::class,'paymentStatus'])->name('payment.status');

Route::get('/payment/{order_id}', [\App\Http\Controllers\PaymentController::class,'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [\App\Http\Controllers\PaymentController::class,'success'])->name('checkout.success');

Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle'])->name('midtrans.callback')->withoutMiddleware('VerifyCsrfToken');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');
Route::post('/logout', function() {
    \Illuminate\Support\Facades\Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// SSO Google Login
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/google', [SocialiteController::class, 'redirectToGoogle'])->name('google');
    Route::get('/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('google.callback');
});

// Review Routes (harus login)
Route::middleware(['auth'])->group(function () {
    Route::post('/events/{event}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// Login publik untuk user biasa (Google SSO)
Route::get('/login', function() {
    if (\Illuminate\Support\Facades\Auth::check()) {
        return redirect()->route('home');
    }
    $categories = \App\Models\Category::all();
    return view('auth.public-login', compact('categories'));
})->name('login');

Route::get('/redirect-if-authed', function() {
    return redirect()->route('home');
})->name('redirect.if.authed');

// Coupon validation API (public access for checkout)
Route::post('/coupon/validate', [\App\Http\Controllers\PaymentController::class, 'validateCoupon'])->name('coupon.validate');

// Public file streaming for posters stored in storage/app/public.
Route::get('/media/storage/{path}', function (string $path) {
    $fullPath = storage_path('app/public/' . $path);

    abort_unless(file_exists($fullPath), 404);

    return response()->file($fullPath);
})->where('path', '.*')->name('media.storage');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Login/logout bebas akses
    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

    // Hanya superadmin yang bisa manage organizers & all data
    Route::middleware(['auth', 'admin'])->group(function () {
        // Superadmin manage organizers
        Route::get('/organizers', [OrganizerController::class, 'index'])->name('organizers.index');
        Route::get('/organizers/create', [OrganizerController::class, 'create'])->name('organizers.create');
        Route::post('/organizers', [OrganizerController::class, 'store'])->name('organizers.store');
        Route::get('/organizers/{user}/edit', [OrganizerController::class, 'edit'])->name('organizers.edit');
        Route::put('/organizers/{user}', [OrganizerController::class, 'update'])->name('organizers.update');
        Route::delete('/organizers/{user}', [OrganizerController::class, 'destroy'])->name('organizers.destroy');

        // Superadmin dashboard & management
        Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
        Route::get('/', [DashboardController::class,'index'])->name('dashboard.home');
        Route::get('/events', [DashboardController::class,'indexEvent'])->name('events.index');
        Route::get('/transactions', [DashboardController::class,'indexTransaction'])->name('transactions.index');

        Route::resource('events', EventAdminController::class);
        Route::resource('partners', PartnerController::class);
        Route::resource('categories', CategoryController::class);
    });

    // Coupon management (superadmin & organizer)
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->middleware('auth');

// Check-in Scanner (superadmin & organizer)
    Route::middleware(['auth'])->group(function () {
        Route::get('/checkin-scanner', [\App\Http\Controllers\CheckinScannerController::class, 'index'])->name('checkin.scanner');
        Route::post('/checkin/verify', [\App\Http\Controllers\CheckinScannerController::class, 'verify'])->name('checkin.verify');
        Route::post('/checkin/manual', [\App\Http\Controllers\CheckinScannerController::class, 'manualCheckin'])->name('checkin.manual');
    });

    // Organizer routes (bisa manage event miliknya sendiri)
    Route::middleware(['auth', 'organizer'])->prefix('organizer')->name('organizer.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'organizerDashboard'])->name('dashboard');
        Route::get('/events', [DashboardController::class, 'organizerEvents'])->name('events');
        Route::get('/transactions', [DashboardController::class, 'organizerTransactions'])->name('transactions');
    });
});
