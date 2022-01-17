<?php

namespace App\Domain\Quiz\Repository\Interfaces;

interface AuthRepositoryInterface
{
    public function isCustomerAuthorizedForCreatingQuiz(): bool;

    public function setAuthorizationKeyForCreatingQuiz(): void;
}
