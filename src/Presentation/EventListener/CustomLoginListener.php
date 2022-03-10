<?php

declare(strict_types=1);

namespace App\Presentation\EventListener;

use App\Domain\QuizSession\Service\QuizSessionService;
use App\Infrastructure\DB\Customer\Customer;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class CustomLoginListener
{
    use TargetPathTrait;

    private QuizSessionService $quizSessionService;

    public function __construct(QuizSessionService $quizSessionService)
    {
        $this->quizSessionService = $quizSessionService;
    }

    public function onLoginEvent(LoginSuccessEvent $loginSuccessEvent): void
    {
        /** @var Customer $customer */
        $customer = $loginSuccessEvent->getUser();
        $this->quizSessionService->restoreNotFinishedQuizzes($customer->getId());
    }
}
