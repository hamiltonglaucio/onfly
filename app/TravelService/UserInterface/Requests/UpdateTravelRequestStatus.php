<?php

namespace App\TravelService\UserInterface\Requests;

use App\TravelService\Domain\Entities\TravelRequest;
use App\TravelService\Infrastructure\Repositories\TravelRequestEloquentRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTravelRequestStatus extends FormRequest
{
    protected TravelRequest $travelRequest;

    public function authorize(): bool
    {
        $this->travelRequest = $this->getTravelRequest();

        if (!$this->travelRequest) {
            return false;
        }

        if ($this->travelRequest->getUserId() === $this->user()->id) {
            return false;
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['approved', 'cancelled'])],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be either approved or cancelled.',
        ];
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('You are not authorized to update this travel request.');
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $current = $this->travelRequest->getStatus();
            $new = $this->input('status');

            if ($current === 'cancelled') {
                $validator->errors()->add('status', 'A cancelled request cannot be updated.');
            }

            if ($current === 'approved' && $new !== 'cancelled') {
                $validator->errors()->add('status', 'An approved request can only be cancelled.');
            }

            if ($current === 'request' && !in_array($new, ['approved', 'cancelled'])) {
                $validator->errors()->add('status', 'A request can only be approved or cancelled.');
            }
        });
    }

    private function getTravelRequest()
    {
        $id = $this->route('id');

        return app(TravelRequestEloquentRepository::class)->findById($id);
    }
}
