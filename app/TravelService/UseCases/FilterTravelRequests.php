<?php

namespace App\TravelService\UseCases;

use App\TravelService\Domain\Contracts\TravelRequestRepositoryInterface;
use Carbon\Carbon;

class FilterTravelRequests
{
    public function __construct(
        private TravelRequestRepositoryInterface $repository
    ) {}

    public function execute(
        ?Carbon $startDate,
        ?Carbon $endDate,
        ?string $destination,
        ?bool $byUser
    )
    {
        $results = $this->repository->filterByPeriodAndDestination(
            $startDate,
            $endDate,
            $destination,
            $byUser
        );

        return $results;
    }
}
