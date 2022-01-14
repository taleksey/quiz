<?php

namespace App\Domain\Quiz\Repository\Interfaces;

interface AuthRepository
{
    public function isCustomerAuthorizedForCreatingQuiz(): bool;

    public function setAuthorizationKeyForCreatingQuiz(): void;
}
