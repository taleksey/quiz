<?php

declare(strict_types=1);

namespace App\Infrastructure\DB\QuizSession;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\QuizSession\Entity\Customer as CustomerDomain;

#[ORM\Entity, ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "customers")]
class Customer extends CustomerDomain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;
}
