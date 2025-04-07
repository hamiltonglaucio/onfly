<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\TravelService\UseCases\GetUserTravelRequests;
use App\TravelService\Domain\Contracts\TravelRequestRepositoryInterface;
use App\TravelService\Domain\Entities\TravelRequest;
use Carbon\Carbon;

class GetUserTravelRequestsTest extends TestCase
{
    public function test_it_returns_requests_for_a_user()
    {
        $userId = 42;

        $requests = [
            new TravelRequest(
                id: 1,
                userId: $userId,
                applicantName: 'Camila Moraes',
                destination: 'Belém',
                departureDate: Carbon::parse('2025-10-01'),
                returnDate: Carbon::parse('2025-10-05'),
                status: 'approved'
            ),
            new TravelRequest(
                id: 2,
                userId: $userId,
                applicantName: 'Camila Moraes',
                destination: 'Manaus',
                departureDate: Carbon::parse('2025-11-01'),
                returnDate: Carbon::parse('2025-11-05'),
                status: 'pending'
            )
        ];

        $mock = $this->createMock(TravelRequestRepositoryInterface::class);

        $mock->expects($this->once())
            ->method('findByUserId')
            ->with($userId)
            ->willReturn($requests);

        $useCase = new GetUserTravelRequests($mock);
        $result = $useCase->execute($userId);

        $this->assertCount(2, $result);
        $this->assertEquals('Belém', $result[0]->getDestination());
        $this->assertEquals('Manaus', $result[1]->getDestination());
    }
}
