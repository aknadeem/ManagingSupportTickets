<?php

namespace App\Policies\V1;

class TicketPolicy
{
    public string $model = 'Ticket';
    public function __construct()
    {
        //
    }

    public function update($user, $ticket)
    {
        return $user->id === $ticket->user_id;
    }
}
