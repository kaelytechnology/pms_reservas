<?php

namespace Kaely\PmsHotel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FoodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled in the controller
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pms_foods', 'name')
                    ->ignore($this->route('food')?->id)
                    ->whereNull('deleted_at')
            ],
            'description' => 'nullable|string|max:1000',
        ];

        // Adjust rules for update requests
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['name'] = [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('pms_foods', 'name')
                    ->ignore($this->route('food')?->id)
                    ->whereNull('deleted_at')
            ];
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'description' => 'descripción',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.unique' => 'Este nombre ya está en uso.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'description.max' => 'La descripción no puede tener más de 1000 caracteres.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean and format the name
        if ($this->has('name')) {
            $this->merge([
                'name' => trim($this->input('name'))
            ]);
        }

        // Clean description
        if ($this->has('description')) {
            $this->merge([
                'description' => trim($this->input('description')) ?: null
            ]);
        }
    }
}