<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasLastLogin
{
    public static function bootHasLastLogin()
    {
        static::creating(function ($model) {
            $model->last_login_at = $model->freshTimestamp();
        });
    }

    /**
     * Update the user's last login timestamp.
     *
     * @return bool
     */
    public function updateLastLogin()
    {
        return $this->forceFill([
            'last_login_at' => $this->freshTimestamp()
        ])->save();
    }

    /**
     * Get the name of the "last login" column.
     *
     * @return string
     */
    public function getLastLoginColumnName()
    {
        return 'last_login_at';
    }
}
