<?php

namespace App\TravelService\Infrastructure\Mappers;

use App\TravelService\Domain\Entities\TravelRequest as DomainTravelRequest;
use App\TravelService\Infrastructure\Eloquent\TravelRequest as EloquentTravelRequest;

class TravelRequestEloquentMapper
{
    public static function toDomain(EloquentTravelRequest $eloquent): DomainTravelRequest
    {
        return new DomainTravelRequest(
            id: $eloquent->id,
            userId: $eloquent->user_id,
            applicantName: $eloquent->applicant_name,
            destination: $eloquent->destination,
            departureDate: $eloquent->departure_date,
            returnDate: $eloquent->return_date,
            status: $eloquent->status,
        );
    }

    public static function toEloquent(DomainTravelRequest $domain): EloquentTravelRequest
    {
        $eloquent = $domain->getId()
            ? EloquentTravelRequest::find($domain->getId()) ?? new EloquentTravelRequest()
            : new EloquentTravelRequest();

        $eloquent->user_id = $domain->getUserId();
        $eloquent->applicant_name = $domain->getApplicantName();
        $eloquent->destination = $domain->getDestination();
        $eloquent->departure_date  = $domain->getDepartureDate();
        $eloquent->return_date    = $domain->getReturnDate();
        $eloquent->status      = $domain->getStatus();

        return $eloquent;
    }

    public static function manyToDomain(iterable $collection): array
    {
        return collect($collection)
            ->map(function ($model) {
                return self::toDomain($model);
            })
            ->all();
    }
}
