<?php

namespace App\Presentation\DTO;

use App\Domain\Quiz\ValueObject\QuestionStep;
use Symfony\Component\HttpFoundation\Request;

class QuizQuestionAnswerDTO
{
    private int $quizId;
    private int $step;
    private int $answerId;

    public function __construct(Request $request)
    {
        $this->quizId = (int) $request->get('id');
        $this->step = (int) $request->get('step');
        $this->answerId = (int) $request->get('question_' . $this->step);
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
