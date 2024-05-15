<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'user',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                'email_verified_at' => $this->mergeWhen(
                    $request->routeIs('users.*'),[
                        'email_verified_at' => $this->email_verified_at,
                        'updated_at' => $this->updated_at,
                        'createdAt' => $this->created_at,
                    ]
                ),
//                'email_verified_at' => $this->when(
//                    $request->routeIs('users.*'),
//                    $this->email_verified_at
//                ),
//                'updated_at' => $this->when(
//                    $request->routeIs('users.*'),
//                    $this->updated_at
//                ),
//                'createdAt' => $this->when(
//                    $request->routeIs('users.*'),
//                    $this->created_at
//                ),
            ],
            'includes' => TicketResource::collection($this->whenLoaded('tickets')),
            'links' => [
                'self' => route('users.show', ['user' => $this->id]),
            ],
        ];
    }
}
