<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\StoreTicketRequest;
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
        $model = [
            'title' => $request->input('data.attributes.title'),
            'description' => $request->input('data.attributes.description'),
            'status' => $request->input('data.attributes.status'),
            'user_id' => $authorId
        ];

        return new TicketResource(Ticket::create($model));
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
