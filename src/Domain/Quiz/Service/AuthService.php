<?php

namespace App\Domain\Quiz\Service;

use App\Domain\Quiz\Repository\Interfaces\AuthRepository;

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
}
