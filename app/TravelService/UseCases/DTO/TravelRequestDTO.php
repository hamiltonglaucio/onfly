<?php

namespace App\TravelService\UseCases\DTO;

use Carbon\Carbon;
class TravelRequestDTO
{
    public function __construct(
        public ?int $id,
        public int $user_id,
        public string $applicant_name,
        public string $destination,
        public Carbon $departure_date,
        public Carbon $return_date,
        public string $status = 'request',
    ) {}
}
