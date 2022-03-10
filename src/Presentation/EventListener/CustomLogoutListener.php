<?php

declare(strict_types=1);

namespace App\Presentation\EventListener;

use App\Domain\QuizSession\Service\QuizSessionService;
use App\Infrastructure\DB\Customer\Customer;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class CustomLogoutListener
{
    private QuizSessionService $quizSessionService;

    public function __construct(QuizSessionService $quizSessionService)
    {
        $this->quizSessionService = $quizSessionService;
    }

    public function onLogoutEvent(LogoutEvent $logoutEvent): void
    {
        $sessions = $logoutEvent->getRequest()->getSession()->all();
        /** @var Customer $customer */
        $customer = $logoutEvent->getToken()->getUser();
        $this->quizSessionService->saveNotFinishedQuizzes($sessions, $customer->getId());
    }
}
