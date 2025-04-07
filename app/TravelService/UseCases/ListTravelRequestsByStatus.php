<?php

namespace App\TravelService\UseCases;

use App\TravelService\Domain\Contracts\TravelRequestRepositoryInterface;
use App\TravelService\Infrastructure\Eloquent\TravelRequest;

class ListTravelRequestsByStatus
{
    public function __construct(
        private TravelRequestRepositoryInterface $repository,
    ){}

    public function execute(string $status): array
    {
        if(!$status)
        {
            return $this->repository->all();
        }
        $travelRequests = $this->repository->findByStatus($status);

        return $travelRequests;
    }
}
