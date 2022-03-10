<?php

declare(strict_types=1);

namespace App\Domain\Quiz\ValueObject;

class QuizAnswer
{
    private int $key;

    private bool $value;

    public function __construct(int $key, bool $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function getPosition(): int
    {
        return $this->key;
    }

    public function isCorrectAnswer(): bool
    {
        return $this->value;
    }
}
