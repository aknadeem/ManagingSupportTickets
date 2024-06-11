<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseUserRequest extends FormRequest
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
            'data.attributes.title' => 'title',
            'data.attributes.description' => 'description',
            'data.attributes.status' => 'status',
            'data.attributes.created_at' => 'created_at',
            'data.attributes.updated_at' => 'updated_at',
            'data.relationships.user.data.id' => 'user_id',
        ], $otherAttributes);

        $attributesToUpdate = [];
        foreach ($attributeMap as $key => $value) {
            if ($this->has($key)) {
                $attributesToUpdate[$value] = $this->input($key);
            }
        }

        return $attributesToUpdate;
    }
}
