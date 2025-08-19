<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncomingLetterController;
use App\Models\IncomingLetter;

Route::get('/', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/register', [AuthController::class, 'registerView']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('role:admin,user')->group(function() {
    // Route::get('/dashboard-total', function (){
    //     return view('pages.dashboard');
    // });
    // Route::get('/dashboard', [DashboardController::class, 'getTotalAduanUsers'])->name('dashboard');
    // Route::get('/get-new-complaint', [DashboardController::class, 'getResident']);
    Route::get('/notifications', function() {
        return view('pages.notifications');
    });
    Route::get('/profile', [UserController::class, 'profileView']);
    Route::post('/change-profile/{id}', [UserController::class, 'profileUpdate']);
    Route::get('/password', [UserController::class, 'PasswordView']);
    Route::post('/change-password/{id}', [UserController::class, 'changePassword']);
    Route::get('/complaint', [ComplaintController::class, 'complaintView']);
    Route::get('/letter', [IncomingLetterController::class, 'index']);
    Route::post('/notification/{id}/read', [UserController::class, 'notificationAlert']);
});

// Route::get('/notifications', function() {
//     return view('pages.notifications');
// });

Route::middleware('role:admin')->group(function() {
    Route::get('/admin/dashboard', [DashboardController::class, 'getDashboardAdmin'])->name('admin.dashboard');
    Route::get('/resident', [ResidentController::class, 'index']);
    Route::get('/resident/create', [ResidentController::class, 'create']);
    Route::get('/resident/{id}/edit', [ResidentController::class, 'edit']);
    Route::post('/resident', [ResidentController::class, 'store']);
    Route::put('/resident/{id}', [ResidentController::class, 'update']);
    Route::delete('/resident/{id}', [ResidentController::class, 'destroy']);
    Route::get('/account-list', [UserController::class, 'accountListView']);
    Route::get('/account-request', [UserController::class, 'accountRequestView']);
    Route::post('/account-request/approval/{id}', [UserController::class, 'account_approval']);
    Route::post('/complaint/update-status/{id}', [ComplaintController::class, 'update_status']);
    Route::get('/letter/{id}/edit', [IncomingLetterController::class, 'edit']);
    Route::put('/letter/update/{id}', [IncomingLetterController::class, 'update']);
});

Route::middleware('role:user')->group(function() {
    Route::get('/user/dashboard', [DashboardController::class, 'getDashboardUser'])->name('user.dashboard');
    Route::get('/complaint/create', [ComplaintController::class, 'create']);
    Route::get('/complaint/{id}/edit', [ComplaintController::class, 'edit']);
    Route::post('/complaint', [ComplaintController::class, 'store']);
    Route::put('/complaint/{id}', [ComplaintController::class, 'update']);
    Route::delete('/complaint/{id}', [ComplaintController::class, 'destroy']);
    Route::get('/letter/create', [IncomingLetterController::class, 'create']);
    Route::post('/letter', [IncomingLetterController::class, 'store']);
    Route::get('/letter/{id}/download', [IncomingLetterController::class, 'download']);
});
