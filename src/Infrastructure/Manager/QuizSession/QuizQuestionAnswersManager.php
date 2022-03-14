<?php

declare(strict_types=1);

namespace App\Infrastructure\Manager\QuizSession;

use App\Domain\Quiz\Repository\Interfaces\ResultRepositoryInterface;
use App\Domain\QuizSession\Entity\QuizSession;
use App\Domain\QuizSession\Manager\QuizQuestionAnswersManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class QuizQuestionAnswersManager implements QuizQuestionAnswersManagerInterface
{
    private SessionInterface $manager;

    public function __construct(
        private ResultRepositoryInterface $resultRepository,
        RequestStack $requestStack
    ) {
        $this->manager = $requestStack->getSession();
    }

    public function save(int $quizId, int $step, bool $answerValue): void
    {
        $mainKey = $this->resultRepository->getQuizMainKey($quizId);
        $savedAnswers = $this->resultRepository->getSavedCustomerAnswers($quizId);
        $savedAnswers[$step] = $answerValue;

        $this->manager->set($mainKey, $savedAnswers);
    }

    public function saveQuizStartDate(int $quizId, \DateTime $date): void
    {
        $mainKey = $this->resultRepository->getQuizMainKey($quizId);
        $this->manager->set($mainKey, ['startDate' => $date->format(DATE_ATOM)]);
    }

    public function saveAnswers(QuizSession $quizSession): void
    {
        $mainKey = $this->resultRepository->getQuizMainKey($quizSession->getQuiz()->getId());
        $this->manager->set($mainKey, $quizSession->getSession());
    }

    public function clean(int $quizId): bool
    {
        return (bool) $this->manager->remove($this->resultRepository->getQuizMainKey($quizId));
    }
}
