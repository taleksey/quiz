<?php

declare(strict_types=1);

namespace App\Infrastructure\DB\QuizSession;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\QuizSession\Entity\Quiz as QuizDomain;

#[ORM\Entity, ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "quizzes")]
class Quiz extends QuizDomain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;
}
