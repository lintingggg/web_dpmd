<?php

namespace App\Policies;

use App\Models\PengaturanBeranda;
use App\Models\User;

class PengaturanBerandaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'superadmin' || $user->role === 'admin';
    }

    public function view(User $user, PengaturanBeranda $pengaturan): bool
    {
        return $user->role === 'superadmin' || $user->role === 'admin';
    }

    public function update(User $user): bool
    {
        return $user->role === 'superadmin' || $user->role === 'admin';
    }
}
