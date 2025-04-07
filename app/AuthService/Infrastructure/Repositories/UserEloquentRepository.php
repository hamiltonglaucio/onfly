<?php

namespace App\AuthService\Infrastructure\Repositories;

use App\AuthService\Domain\Contracts\UserRepositoryInterface;
use App\AuthService\Domain\Entities\User as DomainUser;
use App\AuthService\Domain\ValueObjects\Email;
use App\AuthService\Infrastructure\Eloquent\User as EloquentUser;
use App\AuthService\Infrastructure\Mappers\UserEloquentMapper;

class UserEloquentRepository implements UserRepositoryInterface
{
    public function save(DomainUser $user): DomainUser
    {
        $eloquentUser = UserEloquentMapper::toEloquent($user);
        $eloquentUser->save();

        $user->setId($eloquentUser->id);

        return $user;
    }

    public function findByEmail(Email $email): ?DomainUser
    {
        $eloquentUser = EloquentUser::where('email', (string) $email)->first();

        return $eloquentUser
            ? UserEloquentMapper::toDomain($eloquentUser)
            : null;
    }
}
