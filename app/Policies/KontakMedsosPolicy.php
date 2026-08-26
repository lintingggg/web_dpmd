<?php

namespace App\Policies;

use App\Models\PengaturanKontak;
use App\Models\User;

class KontakMedsosPolicy
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
