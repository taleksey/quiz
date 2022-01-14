<?php

declare(strict_types=1);

namespace App\Presentation\DTO\Quiz;

use App\Domain\Quiz\ValueObject\QuestionStep;

class QuestionAnswerRequestDTO
{
    private int $quizId;
    private int $step;
    private int $answerId;

    /**
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->quizId = $array['quizId'] ?? 0;
        $this->step = $array['step'] ?? 0;
        $this->answerId = $array['answerId'] ?? 0;
    }

    /**
     * @return int
     */
    public function getQuizId(): int
    {
        return $this->quizId;
    }

    /**
     * @return QuestionStep
     */
    public function getQuestionStep(): QuestionStep
    {
        return new QuestionStep($this->step);
    }

    /**
     * @return int
     */
    public function getAnswerId(): int
    {
        return $this->answerId;
    }

    /**
     * @return bool
     */
    public function isCustomerSelectedAnswer(): bool
    {
        return ! empty($this->getAnswerId());
    }
}
