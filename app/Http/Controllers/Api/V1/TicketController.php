<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Http\Resources\V1\UserResource;
use App\Models\Ticket;
use App\Models\User;

class TicketController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // checking if the include parameter is set in the url e.g. /api/v1/tickets?include=author
        // then we return the tickets with the user relationship
        if($this->includeRelation('author')){
            return TicketResource::collection(User::with('tickets')->paginate());
        }
        return TicketResource::collection(Ticket::paginate());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        // checking if the include parameter is set in the url e.g. /api/v1/tickets?include=author
        // then we return the tickets with the user relationship
        if ($this->includeRelation('author')){
            return new TicketResource($ticket->load('user'));
        }
        return new TicketResource($ticket);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
