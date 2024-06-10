<?php

namespace App\Http\Filters\V1;

class TicketFilter extends QueryFilter
{
    // sortable
    protected $sortable = [
            'title',
            'status',
            'created_at',
            // if column is change
            // createdAt => 'created_at'
        ];
    public function status($status)
    {
        return $this->builder->where('status', $status);
    }

    public function created_at($value)
    {
        $dates = explode(',', $value);
        if(count($dates) > 1)
        {
            return $this->builder->whereBetween('created_at', $dates);
        }
        return $this->builder->whereDate('created_at', $value);
    }

    public function include($value)
    {
        return $this->builder->with($value);
    }

    public function title($title)
    {
        $likeStr = str_replace('*', '%', $title);
        return $this->builder->where('title', 'like', $likeStr);
    }

}