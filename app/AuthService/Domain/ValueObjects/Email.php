<?php

namespace App\AuthService\Domain\ValueObjects;

use InvalidArgumentException;

class Email
{
    private string $value;

    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid Email: {$email}");
        }

        $this->value = strtolower(trim($email));
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->getValue();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
