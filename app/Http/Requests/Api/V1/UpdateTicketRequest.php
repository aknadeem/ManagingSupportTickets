<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends BaseTicketRequest
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
            'data.attributes.title' => 'sometimes|string',
            'data.attributes.description' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|string|in:A,C,H,X',
            'data.relationships.user.data.id' => 'sometimes|integer',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'data.attributes.status' => 'The data.attributes.status value is invalid. Please use one of: A, C, H, X',
        ];
    }
}
