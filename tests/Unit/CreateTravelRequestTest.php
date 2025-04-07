<?php

namespace Tests\Unit;

use App\TravelService\Domain\Contracts\TravelRequestRepositoryInterface;
use App\TravelService\Domain\Entities\TravelRequest;
use App\TravelService\UseCases\CreateTravelRequest;
use App\TravelService\UseCases\DTO\TravelRequestDTO;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class CreateTravelRequestTest extends TestCase
{
    public function test_create_travel_request_successfully(): void
    {
        $dto = new TravelRequestDTO(
            id: null,
            user_id: 1,
            applicant_name: 'José da Silva',
            destination: 'São Paulo',
            departure_date: Carbon::parse('2025-05-01'),
            return_date: Carbon::parse('2025-05-03'),
            status: 'request'
        );

        $mock = $this->createMock(TravelRequestRepositoryInterface::class);

        $mock->expects($this->once())
            ->method('save')
            ->willReturn(
                new TravelRequest(
                    id: 1,
                    userId: $dto->user_id,
                    applicantName: $dto->applicant_name,
                    destination: $dto->destination,
                    departureDate: $dto->departure_date,
                    returnDate: $dto->return_date,
                    status: 'pending'
                )
            );

        $useCase = new CreateTravelRequest($mock);
        $result = $useCase->execute($dto);

        $this->assertInstanceOf(TravelRequest::class, $result);
        $this->assertEquals('São Paulo', $result->getDestination());
        $this->assertEquals('pending', $result->getStatus());
    }
}
