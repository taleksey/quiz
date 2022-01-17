<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "customers")]
class Customer
{
    use Timestamps;

    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    /**
     * @var string
     */
    #[ORM\Column(name:"first_name", type: 'string', length: 150)]
    private string $firstName;

    /**
     * @var string
     */
    #[ORM\Column(name:"last_name", type: 'string', length: 150)]
    private string $lastName;

    /**
     * @var  Collection
     */
    #[ORM\OneToMany(mappedBy: "customer", targetEntity: "Quiz")]
    private Collection $quiz;

    /**
     * @var bool
     */
    #[ORM\Column(name:"status", type: 'boolean', options: [
        "default" => 0
    ])]
    private bool $active = true;

    #[ORM\ManyToOne(targetEntity: "CustomerType", inversedBy:"customers")]
    #[ORM\JoinColumn(name: "customer_type", referencedColumnName: "id")]
    private $customerType;

    public function __construct()
    {
        $this->quiz = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return Collection
     */
    public function getQuiz(): Collection
    {
        return $this->quiz;
    }

    /**
     * @param Quiz $quiz
     */
    public function setQuiz(Quiz $quiz): void
    {
        $this->quiz->add($quiz);
    }

    /**
     * @return CustomerType
     */
    public function getCustomerType(): CustomerType
    {
        return $this->customerType;
    }

    /**
     * @param CustomerType $customerType
     */
    public function setCustomerType(CustomerType $customerType): void
    {
        $this->customerType = $customerType;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
