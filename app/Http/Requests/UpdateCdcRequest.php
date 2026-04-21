<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCdcRequest extends StoreCdcRequest
{
    public function rules(): array
    {
        $baseRules = parent::rules();

        return array_merge($baseRules, [
            'fields' => 'nullable|array',
            'fields.*.id' => 'nullable|exists:fields,id',
            'fields.*.name' => 'required_with:fields|string|max:255',
            'fields.*.label' => 'required_with:fields|string|max:255',
            'fields.*.value' => 'nullable|string',

            'new_fields' => 'nullable|array',
            'new_fields.*.name' => 'required_with:new_fields|string|max:255',
            'new_fields.*.label' => 'required_with:new_fields|string|max:255',
            'new_fields.*.value' => 'nullable|string',
            'new_fields.*.field_type_id' => 'required_with:new_fields|exists:field_types,id',
            'deleted_fields' => 'nullable|array',
            'deleted_fields.*' => 'exists:fields,id',
        ]);
    }
}
