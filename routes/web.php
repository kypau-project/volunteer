<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Event\EventIndex;
use App\Livewire\Admin\Event\EventForm;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::get('dashboard', function () {
    if (auth()->user()->isVolunteer()) {
        return redirect()->route('volunteer.dashboard');
    }
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Volunteer Routes
Route::middleware(['auth', 'verified', 'role:volunteer'])->prefix('volunteer')->name('volunteer.')->group(function () {
    // Feature 3: Profil Relawan
    Route::get('profile/edit', \App\Livewire\Volunteer\VolunteerProfileForm::class)->name('profile.edit');
    // Feature 3: Riwayat Partisipasi
    Route::get('dashboard', \App\Livewire\Volunteer\VolunteerDashboard::class)->name('dashboard');
    // Feature 2: Pendaftaran Acara
    Route::get('events', \App\Livewire\Volunteer\EventList::class)->name('events.index');
});

// Admin/Coordinator Routes
Route::middleware(['auth', 'verified', 'role:admin,coordinator'])->prefix('admin')->name('admin.')->group(function () {

    // Event Management (Feature 1)
    Route::get('events', EventIndex::class)->name('events.index');
    Route::get('events/create', EventForm::class)->name('events.create');
    Route::get('events/{event}/edit', EventForm::class)->name('events.edit');

    // Add other admin/coordinator routes here later
    // ...
});

require __DIR__.'/auth.php';

