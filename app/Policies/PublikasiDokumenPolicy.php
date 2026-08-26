<?php

namespace App\Policies;

use App\Models\PublikasiDokumen;
use App\Models\User;

class PublikasiDokumenPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'superadmin' || $user->role === 'admin';
    }

    public function view(User $user, PublikasiDokumen $dokumen): bool
    {
        return $user->role === 'superadmin' || $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'superadmin' || $user->role === 'admin';
    }

    public function update(User $user, PublikasiDokumen $dokumen): bool
    {
        return $user->role === 'superadmin' || $user->role === 'admin';
    }

    public function delete(User $user, PublikasiDokumen $dokumen): bool
    {
        return $user->role === 'superadmin' || $user->role === 'admin';
    }
}
