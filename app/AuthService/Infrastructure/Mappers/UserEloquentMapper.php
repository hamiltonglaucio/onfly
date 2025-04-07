<?php

namespace App\AuthService\Infrastructure\Mappers;

use App\AuthService\Domain\Entities\User as DomainUser;
use App\AuthService\Domain\ValueObjects\Email;
use App\AuthService\Infrastructure\Eloquent\User as EloquentUser;

class UserEloquentMapper
{
    public static function toDomain(EloquentUser $user): DomainUser
    {
        return new DomainUser(
            $user->name,
            new Email($user->email),
            $user->password,
            $user->id
        );
    }

    public static function toEloquent(DomainUser $user): EloquentUser
    {
        $eloquentUser = new EloquentUser();
        $eloquentUser->name     = $user->getName();
        $eloquentUser->email    = (string) $user->getEmail();
        $eloquentUser->password = $user->getPassword();

        return $eloquentUser;
    }
}
