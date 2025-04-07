<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\TravelService\UseCases\GetTravelRequestById;
use App\TravelService\Domain\Contracts\TravelRequestRepositoryInterface;
use App\TravelService\Domain\Entities\TravelRequest;
use Carbon\Carbon;

class GetTravelRequestByIdTest extends TestCase
{
    public function test_it_returns_travel_request_by_id()
    {
        $travelRequest = new TravelRequest(
            id: 1,
            userId: 1,
            applicantName: 'Lucas Andrade',
            destination: 'Curitiba',
            departureDate: Carbon::parse('2025-07-01'),
            returnDate: Carbon::parse('2025-07-05'),
            status: 'pending'
        );

        $mock = $this->createMock(TravelRequestRepositoryInterface::class);

        $mock->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($travelRequest);

        $useCase = new GetTravelRequestById($mock);
        $result = $useCase->execute(1);

        $this->assertInstanceOf(TravelRequest::class, $result);
        $this->assertEquals('Curitiba', $result->getDestination());
    }
}
