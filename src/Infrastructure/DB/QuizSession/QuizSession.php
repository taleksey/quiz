<?php

declare(strict_types=1);

namespace App\Infrastructure\DB\QuizSession;

use App\Domain\QuizSession\Entity\QuizSession as QuizSessionDomain;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\QuizSession\Entity\Customer as CustomerDomain;
use App\Domain\QuizSession\Entity\Quiz as QuizDomain;

#[ORM\Entity, ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "quiz_session")]
class QuizSession extends QuizSessionDomain
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\ManyToOne(targetEntity: Customer::class)]
    #[ORM\JoinColumn(name: "customer_id", referencedColumnName: "id")]
    protected CustomerDomain $customer;

    #[ORM\ManyToOne(targetEntity: Quiz::class)]
    #[ORM\JoinColumn(name: "quiz_id", referencedColumnName: "id")]
    protected QuizDomain $quiz;

    /**
     * @var array<int, string>
     */
    #[ORM\Column(type: 'json')]
    protected array $session = [];
}
