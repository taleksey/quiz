<?php

declare(strict_types=1);

namespace App\Domain\Customer\Entity;

use App\Domain\Customer\Entity\ValueObject\CustomerRole;

class Customer
{
    private int $id;

    private string $email;

    private string $nickname;

    /**
     * @var array<int, string>
     */
    private array $roles = [];

    private string $password;

    private string $firstName;

    private string $lastName;

    private bool $isVerified = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @return array<int, string>
     */
    public function getRoles(): array
    {
        return (new CustomerRole($this->roles))->getRoles();
    }

    /**
     * @param array<int, string> $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getNickName(): string
    {
        return $this->nickname;
    }

    public function setNickName(string $nickname): void
    {
        $this->nickname = $nickname;
    }
}
