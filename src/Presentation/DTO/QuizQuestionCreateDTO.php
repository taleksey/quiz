<?php

namespace App\Presentation\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class QuizQuestionCreateDTO
{
    /**
     * @var string
     */
    #[Assert\NotBlank]
    private string $text;

    /**
     * @var QuizQuestionAnswerCreateDTO[]
     */
    #[Assert\Valid]
    private array $answers;

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @Assert\NotBlank()
     * @Assert\Valid
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }

    /**
     * @param array $answers
     */
    public function setAnswers(array $answers): void
    {
        $this->answers = $answers;
    }
}
