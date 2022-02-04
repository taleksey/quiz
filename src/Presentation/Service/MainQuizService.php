<?php

declare(strict_types=1);

namespace App\Presentation\Service;

use App\Domain\Quiz\Service\QuizService;
use App\Infrastructure\DB\Customer\Customer;
use App\Presentation\DTO\Quiz\CreateDTO;
use App\Presentation\Hydrator\DTOHydrator;

class MainQuizService
{
    public function __construct(
        private DTOHydrator $hydrator,
        private QuizService $quizService
    ) {
    }

    public function createQuiz(CreateDTO $quizCreateDTO, Customer $customer): void
    {
        $totalQuizzes = $this->quizService->getTotalQuizzes();
        $queue = $totalQuizzes + 1;
        $quizEntity = $this->hydrator->hydrate($quizCreateDTO, $queue, $customer->getEmail());
        $this->quizService->save($quizEntity);
    }
}
