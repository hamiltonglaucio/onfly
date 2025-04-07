<?php

namespace App\TravelService\UseCases;

use App\AuthService\Infrastructure\Eloquent\User;
use App\TravelService\Domain\Contracts\TravelRequestRepositoryInterface;
use App\TravelService\Domain\Entities\TravelRequest;
use App\TravelService\UserInterface\Notifications\TravelRequestStatusUpdated;
use Carbon\Carbon;

class CancelApprovedTravelRequest
{
    public function __construct(
        private readonly TravelRequestRepositoryInterface $repository
    )
    {}

    public function execute(int $id):TravelRequest
    {
        $travelRequest = $this->repository->findById($id);

        if ($travelRequest->getStatus() === 'approved') {
            $departure = $travelRequest->getDepartureDate();

            if ($departure->lt(Carbon::today())) {
                throw new \LogicException('You cannot cancel an approved trip that has already started.');
            }
        }

        $travelRequest->setStatus('cancelled');
        $updated = $this->repository->save($travelRequest);

        $user = User::find($updated->getUserId());

        if ($user) {
            $user->notify(new TravelRequestStatusUpdated($updated));
        }

        return $updated;
    }
}
