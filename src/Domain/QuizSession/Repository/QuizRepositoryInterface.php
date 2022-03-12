<?php

declare(strict_types=1);

namespace App\Domain\QuizSession\Repository;

use App\Infrastructure\DB\QuizSession\Quiz;
use App\Domain\QuizSession\Entity\Quiz as QuizDomain;

interface QuizRepositoryInterface
{
    public function getQuiz(QuizDomain $quiz): Quiz;
}
