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
        $authorIdAttribute = $this->routeIs('tickets.store') ? 'data.relationships.user.data.id' : 'author';
        $user = $this->user();

        $authorRule = 'required|integer|exists:users,id';

        $rules = [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            $authorIdAttribute => $authorRule.'|size:'. $user->id, // the author id should be the same as the logged in user, user can create ticket for himself
        ];


        if($user->tokenCan(Abilities::CREATE_OWN_TICKET)){
            // if the user has ability to create the ticket , then it can create the ticket for any one
            $rules[$authorIdAttribute] = $authorRule;
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->routeIs('authors.tickets.store')) {
            $this->merge([
                'author' => $this->user()->id,
            ]);
        }
    }


    public function messages()
    {
        return [
            'data.attributes.status' => 'The data.attributes.status value is invalid. Please use one of: A, C, H, X',
        ];
    }
}
