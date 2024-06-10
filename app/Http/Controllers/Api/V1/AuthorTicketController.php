<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\BaseTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AuthorTicketController extends ApiController
{
    public function index($authorId, TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::where('user_id', $authorId)->filter($filters)->paginate());
    }

    public function store($authorId, StoreTicketRequest $request)
    {
        return new TicketResource(Ticket::create($request->mappedAttributes()));
    }

    public function update(UpdateTicketRequest $request, $authorId, $ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);

            if($ticket->user_id == $authorId) {
                $ticket->update($request->mappedAttributes());
                return new TicketResource($ticket);
            }

            return $this->responseOk('Ticket does not belongs to user');

        } catch (ModelNotFoundException $e) {
            return $this->responseError('Ticket cannot be found', 404);
        }
    }

    public function replace(UpdateTicketRequest $request, $authorId, $ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);

            if($ticket->user_id == $authorId) {
                $ticket->update($request->mappedAttributes());
                return new TicketResource($ticket);
            }

            return $this->responseOk('Ticket does not belongs to user');

        } catch (ModelNotFoundException $e) {
            return $this->responseError('Ticket cannot be found', 404);
        }
    }

    public function destroy($authorId,$ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);

            if($ticket->user_id == $authorId){
                $ticket->delete();
                return $this->responseOk('Ticket deleted successfully');
            }
            return $this->responseError('Ticket not found', 404);
        } catch (ModelNotFoundException $e) {
            return $this->responseError('Ticket not found', 404);
        }
    }

}
