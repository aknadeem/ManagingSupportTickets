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
use App\Policies\V1\TicketPolicy;
use App\Traits\ApiResponses;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AuthorTicketController extends ApiController
{
    protected $policyClass = TicketPolicy::class;
    public function index($authorId, TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::where('user_id', $authorId)->filter($filters)->paginate());
    }

    public function store(StoreTicketRequest $request, $authorId,)
    {
        try {
            $this->isAble('store', Ticket::class);

            return new TicketResource(Ticket::create($request->mappedAttributes(['user' => 'user_id'])));

        } catch (AuthorizationException $e) {
            return $this->responseError('You are not authorized to update that resource', 401);
        }
    }

    public function update(UpdateTicketRequest $request, $authorId, $ticketId)
    {
        try {
            $ticket = Ticket::where('id', $ticketId)->where('user_id', $authorId)->firstOrFail();

            $this->isAble('update', $ticket);

            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);

        } catch (ModelNotFoundException $e) {
            return $this->responseError('Ticket cannot be found', 404);
        } catch (AuthorizationException $e) {
            return $this->responseError('You are not authorized to update that resource', 401);
        }
    }

    public function replace(UpdateTicketRequest $request, $authorId, $ticketId)
    {
        try {
            $ticket = Ticket::where('id', $ticketId)->where('user_id', $authorId)->firstOrFail();

            $this->isAble('replace', $ticket);

            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);

        } catch (ModelNotFoundException $e) {
            return $this->responseError('Ticket cannot be found', 404);
        } catch (AuthorizationException $e) {
            return $this->responseError('You are not authorized to update that resource', 401);
        }
    }

    public function destroy($authorId,$ticketId)
    {
        try {
            $ticket = Ticket::where('id', $ticketId)->where('user_id', $authorId)->firstOrFail();

            $this->isAble('delete', $ticket);

            $ticket->delete();
            return $this->responseOk('Ticket deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->responseError('Ticket not found', 404);
        } catch (AuthorizationException $e) {
            return $this->responseError('You are not authorized to delete that resource', 401);
        }
    }

}
