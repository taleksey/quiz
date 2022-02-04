<?php

declare(strict_types=1);

namespace App\Presentation\Transformers\Statistic;

use App\Domain\Quiz\Service\QuizQuestionAnswersService;
use App\Presentation\DTO\Statistic\StatisticDTO;

class StatisticTransformer
{
    private const DEFAULT_DATE_TIME = '2020-01-01 00:00:00';

    private QuizQuestionAnswersService $quizQuestionAnswersService;

    public function __construct(QuizQuestionAnswersService $quizQuestionAnswersService)
    {
        $this->quizQuestionAnswersService = $quizQuestionAnswersService;
    }

    public function transform(int $quizId, int $customerId): StatisticDTO
    {
        $result = $this->quizQuestionAnswersService->getAnswersByQuiz($quizId);
        $dateTimeStartQuiz = $result['startDate'] ?? self::DEFAULT_DATE_TIME;

        return new StatisticDTO(['answers' => $result, 'quizId' => $quizId, 'customerId' => $customerId, 'startDate' => $dateTimeStartQuiz]);
    }
}
