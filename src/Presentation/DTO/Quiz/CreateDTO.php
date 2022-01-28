<?php

declare(strict_types=1);

namespace App\Presentation\DTO\Quiz;

use Symfony\Component\Validator\Constraints as Assert;

class CreateDTO
{
    /**
     * @var string|null
     */
    #[Assert\NotBlank]
    public ?string $name;

    /**
     * @var QuestionCreateDTO[]
     */
    #[Assert\NotBlank(message:"Set questions")]
    #[Assert\Valid]
    public array $questions;

    /**
     * @var string
     */
    public string $token;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return QuestionCreateDTO[]
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }

    /**
     * @param array<int, QuestionCreateDTO> $questions
     */
    public function setQuestions(array $questions): void
    {
        $this->questions = $questions;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}
