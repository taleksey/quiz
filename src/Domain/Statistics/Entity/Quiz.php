<?php

declare(strict_types=1);

namespace App\Domain\Statistics\Entity;

class Quiz
{
    protected int $id;

    public function __construct()
    {
        $this->id = 0;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
