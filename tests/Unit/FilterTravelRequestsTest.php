<?php

namespace Tests\Unit;

use App\TravelService\Domain\Contracts\TravelRequestRepositoryInterface;
use App\TravelService\Domain\Entities\TravelRequest;
use App\TravelService\UseCases\FilterTravelRequests;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class FilterTravelRequestsTest extends TestCase
{
    public function test_it_filters_by_destination_and_date_range(): void
    {
        $filters = [
            'destination' => 'Recife',
            'departure_start' => Carbon::parse('2025-06-01'),
            'departure_end' => Carbon::parse('2025-06-30')
        ];

        $matchingRequest = new TravelRequest(
            id: 1,
            userId: 1,
            applicantName: 'Ana Costa',
            destination: 'Recife',
            departureDate: Carbon::parse('2025-06-10'),
            returnDate: Carbon::parse('2025-06-15'),
            status: 'approved'
        );

        $mock = $this->createMock(TravelRequestRepositoryInterface::class);

        $mock->expects($this->once())
            ->method('filterByPeriodAndDestination')
            ->with(
                $filters['departure_start'],
                $filters['departure_end'],
                $filters['destination'],
                false
            )
            ->willReturn([$matchingRequest]);

        $useCase = new FilterTravelRequests($mock);
        $results = $useCase->execute(
            startDate: $filters['departure_start'],
            endDate: $filters['departure_end'],
            destination: $filters['destination'],
            byUser: false
        );

        $this->assertCount(1, $results);
        $this->assertEquals('Recife', $results[0]->getDestination());
    }
}
