<?php

namespace App\TravelService\UseCases;

use App\AuthService\Infrastructure\Eloquent\User;
use App\TravelService\Domain\Contracts\TravelRequestRepositoryInterface;
use App\TravelService\Domain\Entities\TravelRequest;
use App\TravelService\UserInterface\Notifications\TravelRequestStatusUpdated;

class UpdateTravelRequestStatus
{
    public function __construct(
        private TravelRequestRepositoryInterface $repository,
    ){}

    public function execute(int $id, string $status): TravelRequest
    {
        $travelRequest = $this->repository->findById($id);
        $travelRequest->setStatus($status);

        $updated = $this->repository->save($travelRequest);

        $user = User::find($updated->getUserId());

        if ($user) {
            $user->notify(new TravelRequestStatusUpdated($updated));
        }

        return $updated;
    }
}
