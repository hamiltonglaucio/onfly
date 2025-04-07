<?php

namespace App\TravelService\UseCases;

use App\TravelService\Domain\Contracts\TravelRequestRepositoryInterface;
use App\TravelService\Domain\Entities\TravelRequest;
use App\TravelService\Infrastructure\Mappers\TravelRequestEloquentMapper;
use App\TravelService\UseCases\DTO\TravelRequestDTO;

readonly class CreateTravelRequest
{
    public function __construct(
        private TravelRequestRepositoryInterface $repository
    ){}

    public function execute(TravelRequestDTO $dto)//: TravelRequest
    {
        $travelRequest = new TravelRequest(
            id: null,
            userId: $dto->user_id,
            applicantName: $dto->applicant_name,
            destination: $dto->destination,
            departureDate: $dto->departure_date,
            returnDate: $dto->return_date,
            status: 'request',
        );

        return $this->repository->save($travelRequest);
    }
}
