<?php

namespace App\Policies;

use App\Models\Berita;
use App\Models\User;

class BeritaPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // As long as authenticated, admin or superadmin can manage Berita
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Berita $berita): bool
    {
        return true;
    }

    public function delete(User $user, Berita $berita): bool
    {
        return true;
    }
}
