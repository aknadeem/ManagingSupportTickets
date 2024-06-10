<?php

namespace App\Policies\V1;

use App\Models\Ticket;
use App\Models\User;
use App\Permissions\V1\Abilities;

class TicketPolicy
{
    public string $model = 'Ticket';
    public function __construct()
    {
        //
    }

    public function store(User $user)
    {
        return $user->tokenCan(Abilities::CREATE_TICKET) || $user->tokenCan(Abilities::CREATE_OWN_TICKET);
    }

    public function replcae(User $user)
    {
        return true;
    }

    public function update(User $user, Ticket $ticket)
    {
        if($user->tokenCan(Abilities::UPDATE_TICKET)){
            return true;
        } else if ($user->tokenCan(Abilities::UPDATE_OWN_TICKET)){
            return $user->id === $ticket->user_id;
        }
        return false;
    }
}
