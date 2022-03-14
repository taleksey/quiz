<?php

declare(strict_types=1);

namespace App\Infrastructure\Manager\Quiz;

use App\Domain\Quiz\Entity\Quiz;
use App\Domain\Quiz\Manager\QuizManagerInterface;
use Doctrine\Persistence\ObjectManager;

class QuizManager implements QuizManagerInterface
{
    public function __construct(private ObjectManager $objectManager)
    {
    }

    public function save(Quiz $quiz): void
    {
        $this->objectManager->persist($quiz);
        $this->objectManager->flush();
    }
}
