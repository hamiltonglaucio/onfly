<?php

namespace App\TravelService\UserInterface\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTravelRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id',
            'applicant_name'  => 'required|string|max:255',
            'destination'    => 'required|string|max:255',
            'departure_date'  => 'required|date|after_or_equal:today',
            'return_date'     => 'required|date|after_or_equal:departure_date',
        ];
    }

    public function messages(): array
    {
        return [
            'applicant_name.required' => 'The name is required.',
            'destination.required' => 'The destination is required.',
            'departure_date.required'  => 'The start date is required.',
            'departure_date.date'      => 'The start date must be a valid date.',
            'return_date.required'    => 'The return date is required.',
            'return_date.date'        => 'The return date must be a valid date.',
            'return_date.after_or_equal' => 'The return date must be after or equal to the start date.',
        ];
    }
}
