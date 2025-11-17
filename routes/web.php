<?php

use Illuminate\Support\Facades\Auth;
use App\Livewire\Admin\DashboardAdmin;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Umum\Profile;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\ListUser;
use App\Livewire\Admin\Event\EventIndex as AdminEventIndex;
use App\Livewire\Admin\Event\EventForm as AdminEventForm;
use App\Livewire\Volunteer\DashboardVolunteer;
use App\Livewire\Volunteer\EventList;
use App\Livewire\Volunteer\MyCertificates;
use \App\Livewire\Volunteer\FeedbackForm;
use App\Livewire\Coordinator\DashboardCoordinator;
use App\Livewire\Coordinator\Attendance\AttendanceManager;
use \App\Livewire\Coordinator\ListVolunteer;
use App\Livewire\Coordinator\Event\EventIndex as CoordinatorEventIndex;
use App\Livewire\Coordinator\Event\EventForm as CoordinatorEventForm;
use App\Http\Middleware\Authenticate;
use App\Http\Controllers\CertificateController;
use \App\Livewire\Volunteer\AttendanceCode;
use  \App\Http\Middleware\VolunteerMiddleware;
use \App\Livewire\Coordinator\CertificateGenerator;
use App\Http\Middleware\CoordinatorMiddleware;

// Public Routes
Route::view('/', 'welcome');

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', DashboardAdmin::class)->name('dashboard');
    Route::get('/list-user', ListUser::class)->name('list-user');
    Route::get('/my-profile', Profile::class)->name('profile');
    Route::get('events', AdminEventIndex::class)->name('events.index');
    Route::get('events/create', AdminEventForm::class)->name('events.create');
    Route::get('events/{event}/edit', AdminEventForm::class)->name('events.edit');
    Route::get('list-users', ListUser::class)->name('users.index');
    Route::get('list-users/{user}/edit', ListUser::class)->name('users.edit');
});

Route::prefix('coordinator')->middleware(['auth', CoordinatorMiddleware::class])->name('coordinator.')->group(function () {
    Route::get('/', DashboardCoordinator::class)->name('dashboard');
    Route::get('events', CoordinatorEventIndex::class)->name('events.index');
    Route::get('events/create', CoordinatorEventForm::class)->name('events.create');
    Route::get('events/{event}/edit', CoordinatorEventForm::class)->name('events.edit');
    Route::get('events/{event}/attendance', AttendanceManager::class)->name('events.attendance');
    // Route to open the Livewire certificate generator for a registration
    Route::get('registrations/{registration}/certificate', CertificateGenerator::class)
        ->name('registrations.certificate');
    Route::get('/my-profile', Profile::class)->name('profile');
    Route::get('volunteers', ListVolunteer::class)->name('volunteers.index');
});

Route::prefix('volunteer')->middleware(['auth', VolunteerMiddleware::class])->name('volunteer.')->group(function () {
    Route::get('/', DashboardVolunteer::class)->name('dashboard');
    Route::get('/my-profile', Profile::class)->name('profile');
    Route::get("events", EventList::class)->name("events.index");
    Route::get("registrations/{registration}/feedback/create", FeedbackForm::class)->name("feedback.create");
    Route::get("registrations/{registration}/attendance", AttendanceCode::class)->name("registrations.attendance");
    Route::get("certificates", MyCertificates::class)->name("certificates.index");
});

Route::get('/auth/start-session', Login::class)->name('login')->middleware('guest');
Route::get('auth/register', Register::class)->name('register')->middleware('guest');
// Certificate download route (used after generating certificates)
Route::get('certificates/{certificate}/download', [CertificateController::class, 'download'])
    ->name('certificates.download')
    ->middleware('auth');
Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/auth/start-session');
})->name('logout')->middleware('auth');
