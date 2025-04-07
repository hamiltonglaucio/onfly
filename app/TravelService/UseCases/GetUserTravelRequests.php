<?php

namespace App\TravelService\UseCases;

use App\TravelService\Domain\Contracts\TravelRequestRepositoryInterface;
use App\TravelService\Domain\Entities\TravelRequest;

class GetUserTravelRequests
{
    public function __construct(
        private TravelRequestRepositoryInterface $repository
    ){}

    public function execute(int $userId)
    {
        return $this->repository->findByUserId($userId);
    }
}
