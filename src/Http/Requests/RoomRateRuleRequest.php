<?php

namespace Kaely\PmsHotel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Kaely\PmsHotel\Models\RoomRateRule;

class RoomRateRuleRequest extends FormRequest
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
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pms_room_rate_rules', 'code')
                    ->ignore($this->route('room_rate_rule')?->id)
                    ->whereNull('deleted_at')
            ],
            'class' => 'required|string|max:100',
            'room_type_name' => 'required|string|max:100',
            'min_nights' => 'required|integer|min:1|max:365',
            'max_guests' => 'required|integer|min:1|max:50',
            'included_dinners' => 'nullable|integer|min:0|max:10',
            'rule_text' => 'nullable|string|max:1000',
            'is_active' => 'sometimes|boolean',
        ];

        // Adjust rules for update requests
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['code'] = [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('pms_room_rate_rules', 'code')
                    ->ignore($this->route('room_rate_rule')?->id)
                    ->whereNull('deleted_at')
            ];
            $rules['class'] = 'sometimes|string|max:100';
            $rules['room_type_name'] = 'sometimes|string|max:100';
            $rules['min_nights'] = 'sometimes|integer|min:1|max:365';
            $rules['max_guests'] = 'sometimes|integer|min:1|max:50';
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'code' => 'código',
            'class' => 'clase',
            'room_type_name' => 'nombre del tipo de habitación',
            'min_nights' => 'noches mínimas',
            'max_guests' => 'huéspedes máximos',
            'included_dinners' => 'cenas incluidas',
            'rule_text' => 'texto de la regla',
            'is_active' => 'activo',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'El código es obligatorio.',
            'code.unique' => 'Este código ya está en uso.',
            'code.max' => 'El código no puede tener más de 50 caracteres.',
            'class.required' => 'La clase es obligatoria.',
            'class.max' => 'La clase no puede tener más de 100 caracteres.',
            'room_type_name.required' => 'El nombre del tipo de habitación es obligatorio.',
            'room_type_name.max' => 'El nombre del tipo de habitación no puede tener más de 100 caracteres.',
            'min_nights.required' => 'Las noches mínimas son obligatorias.',
            'min_nights.integer' => 'Las noches mínimas deben ser un número entero.',
            'min_nights.min' => 'Las noches mínimas deben ser al menos 1.',
            'min_nights.max' => 'Las noches mínimas no pueden ser más de 365.',
            'max_guests.required' => 'Los huéspedes máximos son obligatorios.',
            'max_guests.integer' => 'Los huéspedes máximos deben ser un número entero.',
            'max_guests.min' => 'Los huéspedes máximos deben ser al menos 1.',
            'max_guests.max' => 'Los huéspedes máximos no pueden ser más de 50.',
            'included_dinners.integer' => 'Las cenas incluidas deben ser un número entero.',
            'included_dinners.min' => 'Las cenas incluidas no pueden ser negativas.',
            'included_dinners.max' => 'Las cenas incluidas no pueden ser más de 10.',
            'rule_text.max' => 'El texto de la regla no puede tener más de 1000 caracteres.',
            'is_active.boolean' => 'El campo activo debe ser verdadero o falso.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default value for is_active if not provided
        if (!$this->has('is_active')) {
            $this->merge(['is_active' => true]);
        }

        // Clean and format the code
        if ($this->has('code')) {
            $this->merge([
                'code' => strtoupper(trim($this->input('code')))
            ]);
        }
    }
}