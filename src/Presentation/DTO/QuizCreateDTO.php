<?php

namespace App\Presentation\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class QuizCreateDTO
{
    /**
     * @var string|null
     */
    #[Assert\NotBlank]
    public ?string $name;

    /**
     * @var QuizQuestionCreateDTO[]
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
     * @return QuizQuestionCreateDTO[]
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }

    /**
     * @param array $questions
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
