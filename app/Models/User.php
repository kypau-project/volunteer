<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Traits\HasLastLogin;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasLastLogin;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'whatsapp',
        'photo',
        'alamat',
        'password',
        'role',
        'last_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login' => 'datetime',
        ];
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }

    public function getAvatarUrl()
    {
        if ($this->photo) {
            return Storage::url('users/' . $this->photo);
        }

        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?background=random&name={$name}";
    }

    // protected static function booted()
    // {
    //     static::created(function ($user) {
    //         if (empty($user->username)){
    //             $base = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '', $user->name));
    //             $username = $base . mt_rand(1, 999);
    //         }
    //         $user->username = $username;
    //         $user->save();
    //     });
    // }

}
