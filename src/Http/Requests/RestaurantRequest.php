<?php

namespace Kaely\PmsHotel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RestaurantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $restaurantId = $this->route('restaurant')?->id;
        
        return [
            'short_name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pms_restaurants', 'short_name')->ignore($restaurantId),
            ],
            'full_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pms_restaurants', 'full_name')->ignore($restaurantId),
            ],
            'food_type' => 'required|integer|exists:pms_food_types,id',
            'min_capacity' => 'required|integer|min:1',
            'max_capacity' => 'required|integer|min:1|gte:min_capacity',
            'total_capacity' => 'required|integer|min:1|gte:max_capacity',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'short_name' => 'nombre corto',
            'full_name' => 'nombre completo',
            'food_type' => 'tipo de comida',
            'min_capacity' => 'capacidad mínima',
            'max_capacity' => 'capacidad máxima',
            'total_capacity' => 'capacidad total',
            'opening_time' => 'hora de apertura',
            'closing_time' => 'hora de cierre',
            'description' => 'descripción',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'short_name.required' => 'El nombre corto es obligatorio.',
            'short_name.unique' => 'Ya existe un restaurante con este nombre corto.',
            'full_name.required' => 'El nombre completo es obligatorio.',
            'full_name.unique' => 'Ya existe un restaurante con este nombre completo.',
            'food_type.required' => 'El tipo de comida es obligatorio.',
            'food_type.exists' => 'El tipo de comida seleccionado no es válido.',
            'min_capacity.required' => 'La capacidad mínima es obligatoria.',
            'min_capacity.min' => 'La capacidad mínima debe ser al menos 1.',
            'max_capacity.required' => 'La capacidad máxima es obligatoria.',
            'max_capacity.gte' => 'La capacidad máxima debe ser mayor o igual a la capacidad mínima.',
            'total_capacity.required' => 'La capacidad total es obligatoria.',
            'total_capacity.gte' => 'La capacidad total debe ser mayor o igual a la capacidad máxima.',
            'opening_time.required' => 'La hora de apertura es obligatoria.',
            'opening_time.date_format' => 'La hora de apertura debe tener el formato HH:MM.',
            'closing_time.required' => 'La hora de cierre es obligatoria.',
            'closing_time.after' => 'La hora de cierre debe ser posterior a la hora de apertura.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'short_name' => trim($this->short_name ?? ''),
            'full_name' => trim($this->full_name ?? ''),
            'description' => trim($this->description ?? '') ?: null,
        ]);
    }
}