<?php

namespace App\TravelService\UseCases;

use App\TravelService\Domain\Contracts\TravelRequestRepositoryInterface;
use App\TravelService\Domain\Entities\TravelRequest;

class GetTravelRequestById
{
    public function __construct(
        private TravelRequestRepositoryInterface $repository,
    ){}

    public function execute(int $id): TravelRequest
    {
        $travelRequest = $this->repository->findById($id);

        return $travelRequest;
    }
}
