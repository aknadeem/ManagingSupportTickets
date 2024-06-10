<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends BaseTicketRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            'data.relationships.user.data.id' => 'required|integer|exists:users,id',
        ];
        $user = $this->user();

        if($this->routeIs('tickets.store'))
        {
            if($user->tokenCan(Abilities::CREATE_OWN_TICKET)){
                // if the user has ability to create the ticket , then the author need to match the id of the loggedIn user
                $rules['data.relationships.user.data.id'] .= '|size:'. $user->id;
            }
        }
        return $rules;
    }


    public function messages()
    {
        return [
            'data.attributes.status' => 'The data.attributes.status value is invalid. Please use one of: A, C, H, X',
        ];
    }
}
