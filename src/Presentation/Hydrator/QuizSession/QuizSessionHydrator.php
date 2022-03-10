<?php

declare(strict_types=1);

namespace App\Presentation\Hydrator\QuizSession;

use App\Domain\QuizSession\Hydrator\QuizSessionHydratorInterface;
use App\Infrastructure\DB\QuizSession\Customer;
use App\Infrastructure\DB\QuizSession\Quiz;
use App\Infrastructure\DB\QuizSession\QuizSession;

class QuizSessionHydrator implements QuizSessionHydratorInterface
{
    /**
     * @param array<int|string, int|string> $session
     * @param int $quizId
     * @param int $customerId
     * @return QuizSession
     */
    public function hydrate(array $session, int $quizId, int $customerId): QuizSession
    {
        $quiz = new Quiz();
        $quiz->setId($quizId);
        $customer = new Customer();
        $customer->setId($customerId);
        $quizSession = new QuizSession();
        $quizSession->setSession($session);
        $quizSession->setQuiz($quiz);
        $quizSession->setCustomer($customer);

        return $quizSession;
    }
}
