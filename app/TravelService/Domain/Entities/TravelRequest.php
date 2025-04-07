<?php

namespace App\TravelService\Domain\Entities;

use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class TravelRequest
{
    public function __construct(
        private ?int $id,
        private int $userId,
        private string $applicantName,
        private string $destination,
        private Carbon $departureDate,
        private Carbon $returnDate,
        private string $status = 'request',
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getApplicantName(): string
    {
        return $this->applicantName;
    }

    public function setApplicantName(string $applicantName): void
    {
        $this->applicantName = $applicantName;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): void
    {
        $this->destination = $destination;
    }

    public function getDepartureDate(): Carbon
    {
        return $this->departureDate;
    }

    public function setDepartureDate(Carbon $departureDate): void
    {
        $this->departureDate = $departureDate;
    }

    public function getReturnDate(): Carbon
    {
        return $this->returnDate;
    }

    public function setReturnDate(Carbon $returnDate): void
    {
        $this->returnDate = $returnDate;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'userId' => $this->getUserId(),
            'applicantName' => $this->getApplicantName(),
            'destination' => $this->getDestination(),
            'departureDate' => $this->getDepartureDate(),
            'returnDate' => $this->getReturnDate(),
            'status' => $this->getStatus(),
        ];
    }
}
