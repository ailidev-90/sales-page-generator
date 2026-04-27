<?php

namespace App\Policies;

use App\Models\SalesPage;
use App\Models\User;

class SalesPagePolicy
{
    public function view(User $user, SalesPage $salesPage): bool
    {
        return $salesPage->user_id === $user->id;
    }

    public function update(User $user, SalesPage $salesPage): bool
    {
        return $salesPage->user_id === $user->id;
    }

    public function delete(User $user, SalesPage $salesPage): bool
    {
        return $salesPage->user_id === $user->id;
    }
}
