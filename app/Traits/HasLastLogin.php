<?php

namespace App\Traits;

trait HasLastLogin
{
    public function updateLastLogin()
    {
        $this->last_login = now();
        $this->save();
    }
}
