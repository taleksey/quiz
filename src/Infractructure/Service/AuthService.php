<?php

declare(strict_types=1);

namespace App\Infractructure\Service;

use App\Domain\Quiz\Repository\Interfaces\AuthRepositoryInterface;
use App\Presentation\DTO\Auth\AuthorizationDTO;

class AuthService
{
    private AuthRepositoryInterface $authRepository;
    private string $authToken;

    public function __construct(AuthRepositoryInterface $authRepository, string $authToken)
    {
        $this->authRepository = $authRepository;
        $this->authToken = $authToken;
    }

    public function isCustomerAuthorizedForCreatingQuiz(): bool
    {
        return $this->authRepository->isCustomerAuthorizedForCreatingQuiz();
    }

    public function setAuthorizationKeyForCreatingQuiz(): void
    {
        $this->authRepository->setAuthorizationKeyForCreatingQuiz();
    }

    public function authorize(AuthorizationDTO $authorizationDTO): bool
    {
        if ($authorizationDTO->getToken() !== trim($this->authToken)) {
            return false;
        }

        $this->setAuthorizationKeyForCreatingQuiz();

        return true;
    }
}
