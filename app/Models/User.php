<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasLastLogin;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasLastLogin;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'photo',
        'last_login_at',
        'is_blocked',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
    ];

    // Relations

    /**
     * Get the volunteer profile associated with the user.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(VolunteerProfile::class);
    }

    /**
     * Get the events created by the user (if coordinator/admin).
     */
    public function createdEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    /**
     * Get the registrations for the user.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the attendances verified by the user (if coordinator/admin).
     */
    public function verifiedAttendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'verified_by');
    }

    // Accessors

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCoordinator(): bool
    {
        return $this->role === 'coordinator' || $this->isAdmin();
    }

    public function isVolunteer(): bool
    {
        return $this->role === 'volunteer';
    }

    /**
     * Check if the volunteer profile is complete.
     * Required fields: name, email, phone, photo, address, birth_date, gender, education, institution, and skills
     */
    public function hasCompleteProfile(): bool
    {
        // Basic user fields
        if (empty($this->name) || empty($this->email) || empty($this->phone)) {
            return false;
        }

        // Require a profile record
        if (!$this->relationLoaded('profile')) {
            $this->load('profile');
        }

        $profile = $this->profile;

        if (!$profile) {
            return false;
        }

        // Check all required profile fields
        if (
            empty(trim((string) $profile->address)) ||
            empty($profile->birth_date) ||
            empty($profile->gender) ||
            empty($profile->education) ||
            empty(trim((string) $profile->institution)) ||
            empty(trim((string) $profile->skills))
        ) {
            return false;
        }

        // Photo (profile picture)
        if (empty($this->photo)) {
            return false;
        }

        return true;
    }
}
