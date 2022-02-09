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
     * @param int $step
     * @param bool $answerValue
     * @return void
     */
    public function save(int $quizId, int $step, bool $answerValue): void
    {
        $mainKey = $this->getQuizMainKey($quizId);
        $savedAnswers = $this->getSavedCustomerAnswers($quizId);
        $savedAnswers[$step] = $answerValue;

        $this->manager->set($mainKey, $savedAnswers);
    }

    public function saveQuizStartDate(int $quizId, \DateTime $date): void
    {
        $mainKey = $this->getQuizMainKey($quizId);
        $this->manager->set($mainKey, ['startDate' => $date->format(DATE_ATOM)]);
    }

    /**
     * @param int $quizId
     * @return array<int, string>
     */
    public function getSavedCustomerAnswers(int $quizId): array
    {
        return $this->manager->get($this->getQuizMainKey($quizId), []);
    }

    /**
     * @param int $quizId
     * @return bool
     */
    public function clean(int $quizId): bool
    {
        return (bool) $this->manager->remove($this->getQuizMainKey($quizId));
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

    public function saveAnswers(QuizSession $quizSession): void
    {
        $mainKey = $this->getQuizMainKey($quizSession->getQuiz()->getId());
        $this->manager->set($mainKey, $quizSession->getSession());
    }

    /**
     * @param int $quizId
     * @return string
     */
    private function getQuizMainKey(int $quizId): string
    {
        return $this->getPrefixKey() . $quizId;
    }
}
