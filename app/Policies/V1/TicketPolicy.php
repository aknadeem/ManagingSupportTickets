<?php

namespace App\Policies\V1;

use App\Permissions\V1\Abilities;

class TicketPolicy
{
    public string $model = 'Ticket';
    public function __construct()
    {
        //
    }

    public function update($user, $ticket)
    {
        if($user->tokenCan(Abilities::UPDATE_TICKET)){
            return true;
        } else if ($user->tokenCan(Abilities::UPDATE_OWN_TICKET)){
            return $user->id === $ticket->user_id;
        }
        return false;
    }
}
