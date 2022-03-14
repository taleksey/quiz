<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Quiz\Repository\Interfaces\ResultRepositoryInterface;
use App\Domain\Quiz\ValueObject\QuizResult;
use App\Domain\QuizSession\Entity\QuizSession;

class QuizResultRepository extends SessionRepository implements ResultRepositoryInterface
{
    /**
     * @param int $quizId
     * @return array<int, string>
     */
    public function getSavedCustomerAnswers(int $quizId): array
    {
        return $this->manager->get($this->getQuizMainKey($quizId), []);
    }

    public function getQuizResult(int $quizId): QuizResult
    {
        $mainKey = $this->getQuizMainKey($quizId);
        $quizResult = (array)$this->manager->get($mainKey);

        return new QuizResult($quizResult);
    }

    public function getPrefixKey(): string
    {
        return 'Quiz-';
    }

    /**
     * @param int $quizId
     * @return string
     */
    public function getQuizMainKey(int $quizId): string
    {
        return $this->getPrefixKey() . $quizId;
    }
}
