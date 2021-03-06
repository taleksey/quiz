<?php

declare(strict_types=1);

namespace App\Presentation\DTO\Auth;

use Symfony\Component\Validator\Constraints as Assert;

class AuthorizationDTO
{
    #[Assert\NotBlank]
    public string $token;

    /**
     * @return string
     */
    public function getToken(): string
    {
        return trim($this->token);
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}
