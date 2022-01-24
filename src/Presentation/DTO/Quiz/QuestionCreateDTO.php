<?php

declare(strict_types=1);

namespace App\Presentation\DTO\Quiz;

use Symfony\Component\Validator\Constraints as Assert;

class QuestionCreateDTO
{
    /**
     * @var string
     */
    #[Assert\NotBlank]
    private string $text;

    /**
     * @var QuestionAnswerCreateDTO[]
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
     * @return QuestionAnswerCreateDTO[]
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }

    /**
     * @param QuestionAnswerCreateDTO[] $answers
     */
    public function setAnswers(array $answers): void
    {
        $this->answers = $answers;
    }
}
