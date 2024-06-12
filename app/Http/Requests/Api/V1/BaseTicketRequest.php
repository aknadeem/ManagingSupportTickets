<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function mappedAttributes(array $otherAttributes = []): array
    {
        $attributeMap = array_merge([
            'data.attributes.name' => 'name',
            'data.attributes.email' => 'email',
            'data.attributes.is_manager' => 'is_manager',
            'data.attributes.password' => 'password',
            'data.attributes.created_at' => 'created_at',
            'data.attributes.updated_at' => 'updated_at',
        ], $otherAttributes);

        $attributesToUpdate = [];
        foreach ($attributeMap as $key => $attribute) {
            if ($this->has($key)) {

                $value = $this->input($key);

                if($attribute == 'password'){
                    $value = bcrypt($value);
                }
                $attributesToUpdate[$attribute] = $value;
            }
        }

        return $attributesToUpdate;
    }

    public function messages()
    {
        return [
            'data.attributes.status' => 'The data.attributes.status value is invalid. Please use one of: A, C, H, X',
        ];
    }
}
