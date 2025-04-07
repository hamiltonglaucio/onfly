<?php

namespace App\AuthService\Domain\Contracts;

use App\AuthService\Domain\Entities\User;
use App\AuthService\Domain\ValueObjects\Email;

interface UserRepositoryInterface
{
    public function save(User $user): User;

    public function findByEmail(Email $email): ?User;
}
