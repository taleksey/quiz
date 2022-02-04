<?php

declare(strict_types=1);

namespace App\Infrastructure\DB\Statistics;

use App\Domain\Statistics\Entity\Customer as CustomerDomain;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "customers")]
class Customer extends CustomerDomain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    /**
     * @var Collection<int, QuizStatistic>
     */
    #[ORM\OneToMany(mappedBy: "customer", targetEntity: QuizStatistic::class, cascade: ["persist"])]
    private Collection $statistic;

    #[ORM\Column(name:"first_name", type: 'string', length: 150, nullable: true)]
    private string|null $firstName;

    #[ORM\Column(name:"last_name", type: 'string', length: 150, nullable: true)]
    private string|null $lastName;

    public function __construct()
    {
        $this->id = 0;

        $this->statistic = new ArrayCollection();
    }

    /**
     * @return ArrayCollection<int, QuizStatistic>|Collection<int, QuizStatistic>
     */
    public function getStatistic(): ArrayCollection|Collection
    {
        return $this->statistic;
    }

    /**
     * @param ArrayCollection<int, QuizStatistic>|Collection<int, QuizStatistic> $statistic
     * @return void
     */
    public function setStatistic(ArrayCollection|Collection $statistic): void
    {
        $this->statistic = $statistic;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }
}
