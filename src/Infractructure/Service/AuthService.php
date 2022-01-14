<?php

declare(strict_types=1);

namespace App\Infractructure\Service;

use App\Domain\Quiz\Repository\Interfaces\AuthRepository;
use App\Presentation\DTO\Auth\AuthorizationDTO;

class AuthService
{
    private AuthRepository $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function isCustomerAuthorizedForCreatingQuiz(): bool
    {
        return $this->authRepository->isCustomerAuthorizedForCreatingQuiz();
    }

    public function setAuthorizationKeyForCreatingQuiz()
    {
        $this->authRepository->setAuthorizationKeyForCreatingQuiz();
    }

    public function isEqual(AuthorizationDTO $authorizationDTO, string $authToken): bool
    {
        return $authorizationDTO->getToken() === trim($authToken);
    }
}
