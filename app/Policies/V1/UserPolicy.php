<?php

namespace App\Policies\V1;

use App\Models\Ticket;
use App\Models\User;
use App\Permissions\V1\Abilities;

class UserPolicy
{
    public string $model = 'User';

    public function __construct()
    {
        //
    }

    public function store(User $user)
    {
        return $user->tokenCan(Abilities::CREATE_USER);
    }

    public function replace(User $user)
    {
        return $user->tokenCan(Abilities::REPLACE_USER);
    }
    public function delete(User $user)
    {
        return $user->tokenCan(Abilities::DELETE_USER);
    }

    public function update(User $user)
    {
        return $user->tokenCan(Abilities::UPDATE_USER);
    }
}
