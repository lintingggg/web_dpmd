<?php

namespace App\Policies;

use App\Models\ProfilDinas;
use App\Models\User;

class ProfilDinasPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'superadmin' || $user->role === 'admin';
    }

    public function update(User $user): bool
    {
        return $user->role === 'superadmin' || $user->role === 'admin';
    }
}
