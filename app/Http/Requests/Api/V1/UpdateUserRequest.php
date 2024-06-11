<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends BaseUserRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'data.attributes.name' => 'sometimes|string',
            'data.attributes.email' => 'sometimes|email',
            'data.attributes.is_manager' => 'sometimes|boolean',
            'data.attributes.password' => 'sometimes|string',
        ];
        return $rules;
    }

}
