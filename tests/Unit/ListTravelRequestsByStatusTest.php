<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\TravelService\UseCases\ListTravelRequestsByStatus;
use App\TravelService\Domain\Contracts\TravelRequestRepositoryInterface;
use App\TravelService\Domain\Entities\TravelRequest;
use Carbon\Carbon;

class ListTravelRequestsByStatusTest extends TestCase
{
    public function test_it_lists_requests_by_status()
    {
        $status = 'pending';

        $matchingRequests = [
            new TravelRequest(
                id: 1,
                userId: 1,
                applicantName: 'Joana Lima',
                destination: 'Porto Alegre',
                departureDate: Carbon::parse('2025-08-01'),
                returnDate: Carbon::parse('2025-08-03'),
                status: $status
            ),
            new TravelRequest(
                id: 2,
                userId: 2,
                applicantName: 'Carlos Souza',
                destination: 'São Paulo',
                departureDate: Carbon::parse('2025-08-05'),
                returnDate: Carbon::parse('2025-08-07'),
                status: $status
            )
        ];

        $mock = $this->createMock(TravelRequestRepositoryInterface::class);

        $mock->expects($this->once())
            ->method('findByStatus')
            ->with($status)
            ->willReturn($matchingRequests);

        $useCase = new ListTravelRequestsByStatus($mock);
        $results = $useCase->execute($status);

        $this->assertCount(2, $results);
        $this->assertEquals('pending', $results[0]->getStatus());
        $this->assertEquals('pending', $results[1]->getStatus());
    }
}
