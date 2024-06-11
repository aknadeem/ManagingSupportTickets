<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends BaseUserRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        $rules = [
            'data.attributes.name' => 'required|string',
            'data.attributes.email' => 'required|email',
            'data.attributes.password' => 'required|string',
            'data.attributes.is_manager' => 'nullable|boolean',
        ];
        return $rules;
    }
}
