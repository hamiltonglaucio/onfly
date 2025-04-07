<?php

namespace App\AuthService\UseCases;

use App\AuthService\UseCases\DTO\RegisterUserDTO;
use App\AuthService\Domain\Entities\User;
use App\AuthService\Domain\Contracts\UserRepositoryInterface;

class RegisterUser
{
    public function __construct(
        protected UserRepositoryInterface $repository
    ) {}

    public function handle(RegisterUserDTO $dto): User
    {
        // Verifica se já existe usuário com o mesmo e-mail
        if ($this->repository->findByEmail($dto->email)) {
            throw new \Exception("E-mail já está em uso.");
        }

        // Cria a entidade de domínio
        $user = new User(1,
            name: $dto->name,
            email: $dto->email,
            password: password_hash($dto->password, PASSWORD_DEFAULT)
        );

        // Persiste a entidade no repositório
        return $this->repository->save($user);
    }
}
