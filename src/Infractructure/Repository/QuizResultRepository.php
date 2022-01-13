<?php

declare(strict_types=1);

namespace App\Infractructure\Repository;

class QuizResultRepository extends SessionRepository implements \App\Domain\Quiz\Repository\Interfaces\QuizResultRepository
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

    /**
     * @param int $quizId
     * @return array
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
    /**
     * @param int $quizId
     * @return string
     */
    private function getQuizMainKey(int $quizId): string
    {
        return 'Quiz-' . $quizId;
    }
}
