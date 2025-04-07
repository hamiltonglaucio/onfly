<?php

namespace App\TravelService\Domain\Contracts;

use App\TravelService\Domain\Entities\TravelRequest;
use Carbon\Carbon;

interface TravelRequestRepositoryInterface
{
    public function save(TravelRequest $request): TravelRequest;

    public function findById($id): ?TravelRequest;

    public function findByStatus(string $status): TravelRequest|array|null;

    public function filterByPeriodAndDestination(?Carbon $startDate, ?Carbon $endDate, ?string $destination, ?bool $byUser): array;

    public function all();

    public function findByUserId();
}
