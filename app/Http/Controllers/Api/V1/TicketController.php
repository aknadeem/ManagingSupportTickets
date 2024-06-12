<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\QueryFilter;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\BaseTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Http\Resources\V1\UserResource;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\V1\TicketPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{
    protected $policyClass = TicketPolicy::class;

    /**
     * Get all tickets
     *
     * @group Managing Tickets
     * @queryParam sort string Data field(s) to sort by. Separate multiple fields with commas. Denote descending sort with a minus sign. Example: sort=title,-createdAt
     * @queryParam filter[status] Filter by status code: A, C, H, X. No-example
     * @queryParam filter[title] Filter by title. Wildcards are supported. Example: *fix*
     */
    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
        // checking if the include parameter is set in the url e.g. /api/v1/tickets?include=author
        // then we return the tickets with the user relationship
//        if($this->includeRelation('author')){
//            return TicketResource::collection(Ticket::with('User')->paginate());
//        }
//        return TicketResource::collection(Ticket::paginate());
    }


    /**
     * Create a ticket
     *
     * Creates a new ticket record. Users can only create tickets for themselves. Managers can create tickets for any user.
     *
     * @group Managing Tickets
     *
     * @response {"data":{"type":"ticket","id":107,"attributes":{"title":"asdfasdfasdfasdfasdfsadf","description":"test ticket","status":"A","createdAt":"2024-03-26T04:40:48.000000Z","updatedAt":"2024-03-26T04:40:48.000000Z"},"relationships":{"author":{"data":{"type":"user","id":1},"links":{"self":"http:\/\/localhost:8000\/api\/v1\/authors\/1"}}},"links":{"self":"http:\/\/localhost:8000\/api\/v1\/tickets\/107"}}}
     */
    public function store(StoreTicketRequest $request)
    {
        //        $model = [
        //            'title' => $request->input('data.attributes.title'),
        //            'description' => $request->input('data.attributes.description'),
        //            'status' => $request->input('data.attributes.status'),
        //            'user_id' => $user->id
        //        ];

        if( $this->isAble('store', Ticket::class)){
            return new TicketResource(Ticket::create($request->mappedAttributes()));
        }

        return $this->responseError('You are not authorized to update that resource', 401);
    }

    /**
     * Show a specific ticket.
     *
     * Display an individual ticket.
     *
     * @group Managing Tickets
     */

    public function show($ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);
            // checking if the include parameter is set in the url e.g. /api/v1/tickets?include=author
            // then we return the tickets with the user relationship
            if ($this->includeRelation('author')){
                return new TicketResource($ticket->load('user'));
            }
            return new TicketResource($ticket);
        } catch (ModelNotFoundException $e) {
            return $this->responseError('Ticket cannot be found', 404);
        }
    }


    /**
     * Update Ticket
     *
     * Update the specified ticket in storage.
     *
     * @group Managing Tickets
     *
     */
    public function update(UpdateTicketRequest $request, $ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);

            if($this->isAble('update', $ticket)){
                $ticket->update($request->mappedAttributes());

                return new TicketResource($ticket);
            }
            return $this->responseError('You are not authorized to update that resource', 401);

        } catch (ModelNotFoundException $e) {
            return $this->responseError('Ticket cannot be found', 404);
        }
    }

    /**
     * Replace Ticket
     *
     * Replace the specified ticket in storage.
     *
     * @group Managing Tickets
     *
     */
    public function replace(UpdateTicketRequest $request, $ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);

            if($this->isAble('update', $ticket)) {

                $ticket->update($request->mappedAttributes());
                return new TicketResource($ticket);
            }
            return $this->responseError('You are not authorized to update that resource', 401);

        } catch (ModelNotFoundException $e) {
            return $this->responseError('Ticket cannot be found', 404);
        }
    }

    /**
     * Delete ticket.
     *
     * Remove the specified resource from storage.
     *
     * @group Managing Tickets
     *
     */
    public function destroy($ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);

            if($this->isAble('delete', $ticket)) {
                $ticket->delete();
                return $this->responseOk('Ticket deleted successfully');
            }

            return $this->responseError('You are not authorized to Delete that resource', 401);

        } catch (ModelNotFoundException $e) {
            return $this->responseError('Ticket not found', 404);
        }
    }
}
