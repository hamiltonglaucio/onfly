<?php

namespace App\AuthService\UseCases\DTO;

use App\AuthService\Domain\ValueObjects\Email;

class RegisterUserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly Email $email,
        public readonly string $password
    ) {}
}
