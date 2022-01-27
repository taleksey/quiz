<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Repository\Interfaces;

interface AuthRepositoryInterface
{
    public function isCustomerAuthorizedForCreatingQuiz(): bool;

    public function setAuthorizationKeyForCreatingQuiz(): void;
}
