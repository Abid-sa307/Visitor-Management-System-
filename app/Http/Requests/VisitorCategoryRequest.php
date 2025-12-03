<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisitorCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];

        // For company users, we'll set the company_id from the authenticated user
        if (auth()->guard('company')->check()) {
            $this->merge(['company_id' => auth()->guard('company')->id()]);
        } else {
            $rules['company_id'] = 'nullable|exists:companies,id';
        }

        return $rules;
    }
}