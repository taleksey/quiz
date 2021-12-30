<?php

namespace App\Presentation\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class QuizQuestionAnswerCreateDTO
{
    /**
     * @var string
     */
    #[Assert\NotBlank]
    private string $text;

    /**
     * @var bool
     */
    private bool $correct = false;

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
     * @return bool
     */
    public function isCorrect(): bool
    {
        return $this->correct;
    }

    /**
     * @param bool $correct
     */
    public function setCorrect(bool $correct): void
    {
        $this->correct = $correct;
    }
}
