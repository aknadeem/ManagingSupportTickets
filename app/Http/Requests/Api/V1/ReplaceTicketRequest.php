<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ReplaceTicketRequest extends FormRequest
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
            'data.relationships.user.data.id' => 'required|integer',
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