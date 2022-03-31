<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Manager;

use App\Domain\Quiz\Entity\Quiz;

interface QuizManagerInterface
{
    public function save(Quiz $quiz): void;
}
