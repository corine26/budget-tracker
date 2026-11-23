<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserEntryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetEntryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServiceRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Home: send each visitor to the right page
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return redirect()->route(auth()->user()->isAdmin() ? 'admin.users.index' : 'dashboard');
})->name('home');

/*
|--------------------------------------------------------------------------
| Guests: sign up and log in
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');

    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
});

/*
|--------------------------------------------------------------------------
| Signed-in users
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Income and bills (needs / wants)
    Route::resource('entries', BudgetEntryController::class)->except('show');
    Route::patch('/entries/{entry}/paid', [BudgetEntryController::class, 'togglePaid'])
        ->name('entries.paid');

    // Request system (Laboratories 2-3)
    Route::controller(ServiceRequestController::class)
        ->prefix('requests')->name('requests.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{serviceRequest}', 'show')->name('show');
            Route::patch('/{serviceRequest}/status', 'updateStatus')->name('updateStatus');
        });
});

/*
|--------------------------------------------------------------------------
| Administrator only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

    Route::get('/users/{user}/entries/create', [UserEntryController::class, 'create'])->name('entries.create');
    Route::post('/users/{user}/entries', [UserEntryController::class, 'store'])->name('entries.store');
    Route::get('/entries/{entry}/edit', [UserEntryController::class, 'edit'])->name('entries.edit');
    Route::put('/entries/{entry}', [UserEntryController::class, 'update'])->name('entries.update');
    Route::delete('/entries/{entry}', [UserEntryController::class, 'destroy'])->name('entries.destroy');
    Route::patch('/entries/{entry}/paid', [UserEntryController::class, 'togglePaid'])->name('entries.paid');
});