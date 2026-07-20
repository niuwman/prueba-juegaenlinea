<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreAppointmentRequest extends FormRequest
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
        return [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255|unique:appointments,customer_email',
            'appointment_at' => [
                'required',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) {
                    $date = Carbon::parse($value);

                    // Validar día hábil (Lunes a Viernes)
                    if ($date->isWeekend()) {
                        $fail('Solo se permiten citas en días hábiles (Lunes a Viernes).');
                    }

                    // Validar rango horario (09:00 a 19:00, última cita a las 18:00)
                    $hour = (int) $date->format('H');
                    if ($hour < 9 || $hour > 18) {
                        $fail('El horario de atención es de 09:00 a 19:00 (última cita a las 18:00).');
                    }

                    // Validar que no sea en el pasado
                    if ($date->isPast()) {
                        $fail('No se permiten citas en fechas u horas pasadas.');
                    }

                    // Validar que no esté reservado
                    if (\App\Models\Appointment::where('appointment_at', $value)->exists()) {
                        $fail('Este horario ya está reservado.');
                    }
                }
            ]
        ];

    }

    public function messages(): array
    {
        return [
            'customer_email.unique' => 'Este correo electrónico ya tiene una cita activa en el sistema.',
        ];
    }
}
