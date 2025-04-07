<?php

namespace App\TravelService\Infrastructure\Repositories;

use App\TravelService\Domain\Contracts\TravelRequestRepositoryInterface;
use App\TravelService\Domain\Entities\TravelRequest;
use App\TravelService\Infrastructure\Eloquent\TravelRequest as EloquentTravelRequest;
use App\TravelService\Infrastructure\Mappers\TravelRequestEloquentMapper;
use Carbon\Carbon;

class TravelRequestEloquentRepository implements TravelRequestRepositoryInterface
{
    public function save(TravelRequest $request): TravelRequest
    {
        $eloquent = TravelRequestEloquentMapper::toEloquent($request);

        $domain = TravelRequestEloquentMapper::toDomain($eloquent);
        $eloquent->save();

        return TravelRequestEloquentMapper::toDomain($eloquent);
    }

    public function findById($id): ?TravelRequest
    {
        $eloquent = EloquentTravelRequest::findOrFail($id);

        return TravelRequestEloquentMapper::toDomain($eloquent);
    }

    public function findByStatus(string $status): TravelRequest|array|null
    {
        $eloquent = EloquentTravelRequest::where('status', $status)->get();
        return TravelRequestEloquentMapper::manyToDomain($eloquent);
    }

    public function filterByPeriodAndDestination(?Carbon $startDate, ?Carbon $endDate, ?string $destination, $byUser=true) : array
    {
        $results = EloquentTravelRequest::query()
            ->when($byUser === true, function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->when($startDate, function ($query) use ($startDate) {
                $query->where(function ($q) use ($startDate) {
                    $q->where('departure_date', '>=', $startDate)
                        ->orWhere('return_date', '>=', $startDate);
                });
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->where(function ($q) use ($endDate) {
                    $q->where('departure_date', '<=', $endDate)
                        ->orWhere('return_date', '<=', $endDate);
                });
            })
            ->when($destination, function ($query) use ($destination) {
                $query->where('destination', 'like', "%{$destination}%");
            })
            ->get();

        return TravelRequestEloquentMapper::manyToDomain($results);
    }

    public function all()
    {
        $eloquent = EloquentTravelRequest::all();
        return TravelRequestEloquentMapper::manyToDomain($eloquent);
    }

    public function findByUserId()
    {
        $userId = auth()->id();
        $eloquent = EloquentTravelRequest::where('user_id', $userId)->get();
        return TravelRequestEloquentMapper::manyToDomain($eloquent);
    }
}
