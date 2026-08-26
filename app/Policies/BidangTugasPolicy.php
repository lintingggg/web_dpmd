<?php

namespace App\Policies;

use App\Models\BidangTugas;
use App\Models\User;

class BidangTugasPolicy
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
