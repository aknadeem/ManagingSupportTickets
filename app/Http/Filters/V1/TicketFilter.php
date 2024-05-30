<?php

namespace App\Http\Filters\V1;


class TicketFilter extends QueryFilter
{
    //status
    public function status($status)
    {
        return $this->builder->where('status', $status);
    }

}