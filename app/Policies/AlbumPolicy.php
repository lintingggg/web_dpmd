<?php

namespace App\Policies;

use App\Models\Album;
use App\Models\User;

class AlbumPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'superadmin' || $user->role === 'admin';
    }

    public function view(User $user, Album $album): bool
    {
        return $user->role === 'superadmin' || $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'superadmin' || $user->role === 'admin';
    }

    public function update(User $user, Album $album): bool
    {
        return $user->role === 'superadmin' || $user->role === 'admin';
    }

    public function delete(User $user, Album $album): bool
    {
        return $user->role === 'superadmin' || $user->role === 'admin';
    }
}
