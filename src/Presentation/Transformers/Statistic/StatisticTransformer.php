<?php

declare(strict_types=1);

namespace App\Presentation\Transformers\Statistic;

use App\Domain\Quiz\Service\QuizQuestionAnswersService;
use App\Presentation\DTO\Statistic\StatisticDTO;

class StatisticTransformer
{
    private QuizQuestionAnswersService $quizQuestionAnswersService;

    public function __construct(QuizQuestionAnswersService $quizQuestionAnswersService)
    {
        $this->quizQuestionAnswersService = $quizQuestionAnswersService;
    }

    public function transform(int $quizId, int $customerId): StatisticDTO
    {
        $quizResult = $this->quizQuestionAnswersService->getQuizResult($quizId);

        return new StatisticDTO(['quizResult' => $quizResult, 'quizId' => $quizId, 'customerId' => $customerId]);
    }
}
