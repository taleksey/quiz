<?php

declare(strict_types=1);

namespace App\Infrastructure\DB\Statistics;

use App\Domain\Statistics\Entity\Quiz as QuizDomain;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "quizzes")]
class Quiz extends QuizDomain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;
}
