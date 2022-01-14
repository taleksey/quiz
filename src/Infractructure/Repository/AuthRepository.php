<?php

declare(strict_types=1);

namespace App\Infractructure\Repository;

class AuthRepository extends SessionRepository implements \App\Domain\Quiz\Repository\Interfaces\AuthRepository
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
