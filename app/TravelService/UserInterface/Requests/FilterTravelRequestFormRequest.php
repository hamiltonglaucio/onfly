<?php

namespace App\TravelService\UserInterface\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterTravelRequestFormRequest extends FormRequest
{
    public function rules():array
    {
        return [
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'destination' => ['nullable', 'string'],
            'byUser' => ['nullable', 'boolean'],
        ];
    }

    public function passedValidation(): void
    {
        if ($this->filled('start_date')) {
            $this->merge(['start_date' => \Carbon\Carbon::parse($this->start_date)]);
        }

        if ($this->filled('end_date')) {
            $this->merge(['end_date' => \Carbon\Carbon::parse($this->end_date)]);
        }
    }
}
