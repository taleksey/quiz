<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Quiz\Repository\Interfaces\AuthRepositoryInterface;

class AuthRepository extends SessionRepository implements AuthRepositoryInterface
{
    public function isCustomerAuthorizedForCreatingQuiz(): bool
    {
        return $this->manager->has($this->getAuthorizationKeyForCreatingQuiz());
    }

    public function setAuthorizationKeyForCreatingQuiz(): void
    {
        $this->manager->set($this->getAuthorizationKeyForCreatingQuiz(), true);
    }

    private function getAuthorizationKeyForCreatingQuiz(): string
    {
        return 'authKey';
    }
}
