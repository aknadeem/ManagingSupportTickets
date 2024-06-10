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
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        //        $model = [
        //            'title' => $request->input('data.attributes.title'),
        //            'description' => $request->input('data.attributes.description'),
        //            'status' => $request->input('data.attributes.status'),
        //            'user_id' => $user->id
        //        ];

        try {
            $user = User::findOrFail($request->input('data.relationships.user.data.id'));
            $this->isAble('store', Ticket::class);
            return new TicketResource(Ticket::create($request->mappedAttributes()));

        } catch (AuthorizationException $e) {
            return $this->responseError('You are not authorized to update that resource', 401);
        }
    }

    /**
     * Display the specified resource.
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
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);

//            $model = [
//                'title' => $request->input('data.attributes.title'),
//                'description' => $request->input('data.attributes.description'),
//                'status' => $request->input('data.attributes.status'),
//                'user_id' => $request->input('data.relationships.user.data.id'),
//            ];

            $this->isAble('update', $ticket);
            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);

        } catch (ModelNotFoundException $e) {
            return $this->responseError('Ticket cannot be found', 404);
        } catch (AuthorizationException $e) {
            return $this->responseError('You are not authorized to update that resource', 401);
        }
    }

    public function replace(UpdateTicketRequest $request, $ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);
            $this->isAble('update', $ticket);
            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);

        } catch (ModelNotFoundException $e) {
            return $this->responseError('Ticket cannot be found', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);
            $ticket->delete();
            return $this->responseOk('Ticket deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->responseError('Ticket not found', 404);
        }
    }
}
