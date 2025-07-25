<?php

namespace Kaely\PmsHotel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RestaurantReservationRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'reservation_id' => ['required', 'exists:pms_reservations,id'],
            'restaurant_id' => ['required', 'exists:pms_restaurants,id'],
            'event_id' => ['nullable', 'exists:pms_events,id'],
            'food_id' => ['nullable', 'exists:pms_foods,id'],
            'dessert_id' => ['nullable', 'exists:pms_desserts,id'],
            'beverage_id' => ['nullable', 'exists:pms_beverages,id'],
            'decoration_id' => ['nullable', 'exists:pms_decorations,id'],
            'special_requirement_id' => ['nullable', 'exists:pms_special_requirements,id'],
            'availability_id' => ['nullable', 'exists:pms_restaurant_availabilities,id'],
            'people' => ['required', 'integer', 'min:1', 'max:999'],
            'reservation_date' => ['required', 'date', 'after_or_equal:today'],
            'reservation_time' => ['required', 'date_format:H:i'],
            'status' => ['required', 'string', Rule::in(['pending', 'confirmed', 'cancelled', 'completed'])],
            'comment' => ['nullable', 'string', 'max:1000'],
            'other' => ['nullable', 'string', 'max:500'],
        ];

        // For updates, allow past dates
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['reservation_date'] = ['required', 'date'];
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'reservation_id' => 'reserva',
            'restaurant_id' => 'restaurante',
            'event_id' => 'evento',
            'food_id' => 'comida',
            'dessert_id' => 'postre',
            'beverage_id' => 'bebida',
            'decoration_id' => 'decoración',
            'special_requirement_id' => 'requerimiento especial',
            'availability_id' => 'disponibilidad',
            'people' => 'número de personas',
            'reservation_date' => 'fecha de reserva',
            'reservation_time' => 'hora de reserva',
            'status' => 'estado',
            'comment' => 'comentario',
            'other' => 'otro',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'reservation_id.required' => 'La reserva es obligatoria.',
            'reservation_id.exists' => 'La reserva seleccionada no existe.',
            'restaurant_id.required' => 'El restaurante es obligatorio.',
            'restaurant_id.exists' => 'El restaurante seleccionado no existe.',
            'event_id.exists' => 'El evento seleccionado no existe.',
            'food_id.exists' => 'La comida seleccionada no existe.',
            'dessert_id.exists' => 'El postre seleccionado no existe.',
            'beverage_id.exists' => 'La bebida seleccionada no existe.',
            'decoration_id.exists' => 'La decoración seleccionada no existe.',
            'special_requirement_id.exists' => 'El requerimiento especial seleccionado no existe.',
            'availability_id.exists' => 'La disponibilidad seleccionada no existe.',
            'people.required' => 'El número de personas es obligatorio.',
            'people.integer' => 'El número de personas debe ser un número entero.',
            'people.min' => 'El número de personas debe ser al menos 1.',
            'people.max' => 'El número de personas no puede ser mayor a 999.',
            'reservation_date.required' => 'La fecha de reserva es obligatoria.',
            'reservation_date.date' => 'La fecha de reserva debe ser una fecha válida.',
            'reservation_date.after_or_equal' => 'La fecha de reserva debe ser hoy o una fecha futura.',
            'reservation_time.required' => 'La hora de reserva es obligatoria.',
            'reservation_time.date_format' => 'La hora de reserva debe tener el formato HH:MM.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser: pendiente, confirmado, cancelado o completado.',
            'comment.max' => 'El comentario no puede exceder los 1000 caracteres.',
            'other.max' => 'El campo otro no puede exceder los 500 caracteres.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'comment' => $this->comment ? trim($this->comment) : null,
            'other' => $this->other ? trim($this->other) : null,
        ]);
    }
}